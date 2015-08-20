<?php

class ControllerCartRewardPointsBaseAPI extends ApiController {

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
		// Just a dummy redirect to prevent errors in checkout/reward/reward.
		$this->request->post['redirect'] = 'checkout/cart';

		$data = parent::getInternalRouteData('checkout/reward/reward', true);

		ApiException::evaluateErrors($data);
	}
 
}

?>