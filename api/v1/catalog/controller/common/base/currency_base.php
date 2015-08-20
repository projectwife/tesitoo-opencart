<?php

class ControllerCommonCurrencyBaseAPI extends ApiController {

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
			case 'common/home': // Successfully set currency.
				exit();
				break;
		}
	}

	/**
	 * Resource methods
	 */
	
	public function get() {
		$action = new Action('common/currency');
		$data = $action->execute($this->registry);

		$response['currencies'] = $this->processCurrencies($data['currencies']);

		$this->response->setOutput($response);
	}
	
	public function post() {
		parent::getInternalRouteData('common/currency/currency');
	}

	/**
	 * Helper methods
	 */
	
	protected function processCurrencies($currencies) {
		foreach($currencies as &$currency) {
			if($currency['symbol_left']) {
				$currency['symbol'] = $currency['symbol_left'];
			}
			else {
				$currency['symbol'] = $currency['symbol_right'];
			}

			unset($currency['symbol_left']);
			unset($currency['symbol_right']);
		}

		return $currencies;
	}

}

?>