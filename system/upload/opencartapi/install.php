<?php

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (file_exists('../../../config.php')) {
	require_once('../../../config.php');
}

require_once('filemodifier.class.php');

define('API_VERSION', 'v1');
define('DIR_OPENCART', str_replace('\'', '/', realpath(DIR_APPLICATION . '../')) . '/');
define('DIR_API', str_replace('\'', '/', realpath(dirname(__FILE__) . '/../../../api/')) . '/');
define('DIR_API_VERSION', DIR_API . API_VERSION . '/');

$apiIndexFilePath = DIR_API_VERSION . 'index.php';
$apiConfigFilePath = DIR_API_VERSION . 'config.php';

chmod($apiIndexFilePath, 0755);
chmod($apiConfigFilePath, 0755);

if (!is_writable($apiIndexFilePath)) {
	die('Error: Could not write to index.php please check you have set the correct permissions on: ' . $apiIndexFilePath);
}

if (!is_writable($apiConfigFilePath)) {
	die('Error: Could not write to config.php please check you have set the correct permissions on: ' . $apiConfigFilePath);
}

// index.php
$indexFileModifier = new FileModifier(DIR_OPENCART . 'index.php', $apiIndexFilePath);

$indexFileModifier->replaceLine('require_once(\'./vqmod/vqmod.php\');', 'require_once(\'../../vqmod/vqmod.php\');', true);
$indexFileModifier->replaceLine('rtrim(dirname($_SERVER[\'PHP_SELF\'])', 'rtrim(dirname(dirname(dirname($_SERVER[\'PHP_SELF\'])))');
$indexFileModifier->replaceLine('rtrim(dirname($_SERVER[\'PHP_SELF\']),', 'rtrim(dirname(dirname(dirname($_SERVER[\'PHP_SELF\']))),');
$indexFileModifier->replaceLine('new Loader', 'new ApiLoader');
$indexFileModifier->replaceLine('new Url(', 'new ApiUrl(');
$indexFileModifier->replaceLine('new Request();', 'new ApiRequest($registry);');
$indexFileModifier->replaceLine('new Response();', 'new ApiResponse($registry);');
$indexFileModifier->replaceLine('Content-Type: text/html; charset=utf-8', 'Content-Type: application/json');
$indexFileModifier->replaceLine('$session = new Session();', '$session = new ApiSession($registry);');
$indexFileModifier->replaceLine('new Action(\'common/maintenance\')', 'new ApiAction(\'common/maintenance\')');
$indexFileModifier->replaceLine('new Action($request->get[\'route\'])', 'new ApiAction($request->get[\'route\'])');
$indexFileModifier->replaceLine('new Action(\'common/home\')', 'new ApiAction(\'error/not_found\')');

$indexFileModifier->deleteLine('// SEO URL\'s', 2);
$indexFileModifier->deleteLine('$controller->dispatch($action, new Action(\'error/not_found\'));', 1);
$indexFileModifier->deleteLine('(!isset($request->cookie[\'language\'])', 4, true);
$indexFileModifier->deleteLine('// Tracking Code', 7, true);

$indexFileModifier->addAfterLine('define(\'VERSION\'', 'define(\'APIVERSION\', \'1.3.1\'); ' . "\n");
$indexFileModifier->addAfterLine('DIR_SYSTEM . \'startup.php\'', 'require_once(DIR_API_SYSTEM . \'startup.php\');' . "\n");
$indexFileModifier->addAfterLine('$registry->set(\'response\', $response);', "\n" . '// Auth
$registry->set(\'oauth\', new Authentication($registry));' . "\n");
$indexFileModifier->addAfterLine('$controller->addPreAction(new Action(\'common/seo_url\'));', '// OAuth access token
$controller->addPreAction(new ApiAction(\'oauth2/oauth\'));' . "\n");
$indexFileModifier->addAfterLine('// Dispatch', 'try {
	$controller->dispatch($action, new ApiAction(\'error/not_found\'));
}
catch(ApiException $e) {
	$response->setInterceptOutput(false);
	$response->setHttpResponseCode($e->getHttpResponseCode());
	$response->setOutput(array(\'errors\' => $e->getErrors()));
}
');

if($indexFileModifier->execute() === false) {
	$missingLines = $indexFileModifier->getMissingLines();

	echo "Installation failed, could not find the following line(s) in index.php:\n";
	foreach ($missingLines as $find => $value) {
		echo $find . "\n";
	}
 
	echo "Did you modify the index.php in the root of your Opencart installation and changed the lines above? For support please contact us at <a href=\"mailto:opencartrestapi@gmail.com\">opencartrestapi@gmail.com</a>";

	exit();
}

// config.php
$output  = '<?php' . "\n\n";

$output .= 'if (file_exists(\'../../config.php\')) {' . "\n";
$output .= '	require_once(\'../../config.php\');' . "\n";			
$output .= '}' . "\n\n";

$output .= '// DIR' . "\n";
$output .= 'define(\'DIR_API_APPLICATION\', \'' . DIR_OPENCART . 'api/v1/catalog/\');' . "\n";
$output .= 'define(\'DIR_API_SYSTEM\', \'' . DIR_OPENCART . 'api/v1/system/\');' . "\n\n";

$output .= '?>';

$file = fopen($apiConfigFilePath, 'w');

fwrite($file, $output);
fclose($file);

?>