<?php

class ControllerCommonCustomFieldBaseAPI extends ApiController {

	const CUSTOM_FIELD_LOCATION_ACCOUNT = 'account';
	const CUSTOM_FIELD_LOCATION_ADDRESS = 'address';

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
		$location = NULL;
		if(isset($this->request->get['location'])) {
			$location = $this->request->get['location'];
		}

		$customFields = array('custom_fields' => $this->getCustomFields($location));
		$this->response->setOutput($customFields);
	}

	protected function getCustomFields($location) {
		$this->load->model('account/custom_field');

		if (isset($this->request->get['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->get['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $this->request->get['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}
		
		$customFields = $this->model_account_custom_field->getCustomFields($customer_group_id);

		return $this->processCustomFields($customFields, $location);
	}

	protected function processCustomFields($customFields, $location) {
		foreach($customFields as $key => &$customField) {
			if(isset($location) && $customField['location'] != $location) {
				// Can remove this item since it's not in the given location.
				unset($customFields[$key]);
			}
			else {
				$customField['custom_field_id'] = (int)$customField['custom_field_id'];
				unset($customField['sort_order']);
			}
		}

		return array_values($customFields);
	}
}

?>