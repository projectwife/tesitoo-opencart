<?php

class ControllerAccountDownloadBaseAPI extends ApiController {

	public function index($args = array()) {
		if($this->request->isGetRequest()) {
			$this->get();
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}

	}

	public function redirect($url, $status = 302) {
		switch($url) {
			case 'account/login': // User not logged in
				throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_USER_NOT_LOGGED_IN, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_USER_NOT_LOGGED_IN));
				break;

		}
	}

	/**
	 * Resource methods
	 */
	
	public function get($id = NULL) {
		$this->url->addCatalogLink('account/download/download');

		$data = parent::getInternalRouteData('account/download');

		$downloads = array('downloads' => $this->getDownloads($data));
		$this->response->setOutput($downloads);
	}
	
	/**
	 * Helper methods
	 */

	protected function getDownloads($data) {
		$downloads = array();

		if(isset($data['downloads'])) {
			$downloads = $this->processDownloads($data['downloads']);
		}

		return $downloads;
	}

	protected function processDownloads($downloads) {
		foreach($downloads as &$download) {
			$download['order_id'] = (int)$download['order_id'];
			$download['url'] = $download['href'];

			unset($download['href']);
		}

		return $downloads;
	}
}

?>