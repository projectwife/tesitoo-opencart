<?php

class ControllerCommonLanguageBaseAPI extends ApiController {

	public function index($args = array()) {
		if($this->request->isGetRequest()) {
			$this->get();
		}
		else if($this->request->isPostRequest()) {
			$this->post();
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}
		
	}

	/**
	 * Base methods
	 */

	public function redirect($url, $status = 302) {
		switch($url) {
			case 'common/home': // Successfully set language code.
				exit();
				break;
		}
	}

	/**
	 * Resource methods
	 */
	
	public function get() {
		$action = new Action('common/language');
		$data = $action->execute($this->registry);

		$response['languages'] = $this->processLanguages($data['languages']);

		$this->response->setOutput($response);
	}
	
	public function post() {
		parent::getInternalRouteData('common/language/language');
	}

	/**
	 * Helper methods
	 */
	
	protected function processLanguages($languages) {
		foreach($languages as &$language) {
			$language['image'] = $this->config->get('config_secure') ? HTTPS_SERVER : HTTP_SERVER . 'image/flags/'.$language['image'];
		}

		return $languages;
	}

}

?>