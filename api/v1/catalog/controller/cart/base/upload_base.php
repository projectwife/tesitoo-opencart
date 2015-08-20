<?php

require_once(DIR_API_APPLICATION . 'controller/product/product.php');

class ControllerCartUploadBaseAPI extends ControllerProductProductAPI {

	public function index($args = NULL) {
		if($this->request->isPostRequest()) {
			$this->post();
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}

	}

	/**
	 * Resource methods
	 */

	public function post() {
		$data = parent::getInternalRouteData('tool/upload', true);
		ApiException::evaluateErrors($data);

		$file = array("file" => $this->getFile($data));
		$this->response->setOutput($file);
	}

	/**
	 * Helper methods
	 */
	
	protected function getFile($data) {
		return array("name" => $data['code']);
	}
 
}

?>