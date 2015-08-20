<?php

class ControllerCommonCustomerGroupBaseAPI extends ApiController {

	public function index($args = array()) {
		if($this->request->isGetRequest()) {
			$this->get();
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
		}
		
	}

	/**
	 * Resource methods
	 */
	
	public function get($id = NULL) {
		$customerGroups = array('customer_groups' => $this->getCustomerGroups());
		$this->response->setOutput($customerGroups);
	}

	/**
	 * Helper methods
	 */
	
	protected function getCustomerGroups() {
		$this->load->model('account/customer_group');

		$displayCustomerGroups = array();

		if (is_array($this->config->get('config_customer_group_display'))) {
			$customerGroups = $this->model_account_customer_group->getCustomerGroups();

			foreach ($customerGroups as $customerGroup) {
				if (in_array($customerGroup['customer_group_id'], $this->config->get('config_customer_group_display'))) {
					$displayCustomerGroups[] = $customerGroup;
				}
			}
		}

		return $this->processCustomerGroups($displayCustomerGroups);
	}

	protected function processCustomerGroups($customerGroups) {
		foreach($customerGroups as &$customerGroup) {
			unset($customerGroup['approval']);
			unset($customerGroup['sort_order']);
			unset($customerGroup['language_id']);

			$customerGroup['customer_group_id'] = (int)$customerGroup['customer_group_id'];
		}

		return $customerGroups;
	}
}

?>