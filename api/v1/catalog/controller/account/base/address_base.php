<?php

class ControllerAccountAddressBaseAPI extends ApiController {

	private $defaultParameters = array(
		'company' => '',
		'address_2' => '',
		'postcode' => '',
		'default' => 'false'
	);

	public function index($args = array()) {
		$id = isset($args['id']) ? $args['id'] : null;

		if($this->request->isGetRequest()) {
			$this->get($id);
		}
		else if($this->request->isPostRequest()) {
			$this->post();
		}
		else if($this->request->isPutRequest()) {
			$this->put($id);
		}
		else if($this->request->isDeleteRequest()) {
			$this->delete($id);
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

			case 'account/address': // Success
				break;
		}
	}

	/**
	 * Resource methods
	 */
	
	public function get($id = NULL) {
		// Call index for login check.
		parent::getInternalRouteData('account/address');

		if($id != NULL) {
			$this->request->get['address_id'] = $id;

			$data = parent::getInternalRouteData('account/address/edit');

			$address = array('address' => $this->processAddress($id, $data));
			$this->response->setOutput($address);
		}
		else {
			$addresses = array('addresses' => $this->getAddresses());
			$this->response->setOutput($addresses);
		}
	}

	public function post() {
		$this->request->setDefaultParameters($this->defaultParameters);

		$this->request->convertBoolToYesNoRadioValue('default');

		$data = parent::getInternalRouteData('account/address/add');

		ApiException::evaluateErrors($data);

		// Find id of last added address
		$address_query = $this->db->query("SELECT address_id FROM " . DB_PREFIX . "address WHERE customer_id = '" . (int)$this->customer->getId() . "' ORDER BY address_id DESC LIMIT 0, 1");
		if($address_query->num_rows) {
			$addressId = $address_query->row['address_id'];
		}
		
		$address = array('address' => $this->processAddress($addressId, $data));
		$this->response->setOutput($address);
	}

	public function put($id = NULL) {
		$this->request->setDefaultParameters($this->defaultParameters);

		if($id !== NULL) {
			$this->request->get['address_id'] = $id;
		}

		$this->request->convertBoolToYesNoRadioValue('default');

		$this->request->server['REQUEST_METHOD'] = 'POST';
		$data = parent::getInternalRouteData('account/address/edit');

		ApiException::evaluateErrors($data);

		$address = array('address' => $this->processAddress($id, $data));
		$this->response->setOutput($address);
	}

	public function delete($id = NULL) {
		if($id !== NULL) {
			$this->request->get['address_id'] = $id;
		}

		$data = parent::getInternalRouteData('account/address/delete');
		ApiException::evaluateErrors($data);
	}

	/**
	 * Helper methods
	 */

	protected function getAddresses() {
		$this->load->model('account/address');
		$addresses = $this->model_account_address->getAddresses();
		return $this->processAddresses(array_values($addresses));
	}

	protected function processAddresses($addresses) {
		foreach($addresses as &$address) {
			$address['address_id'] = (int)$address['address_id'];
			$address['zone_id'] = (int)$address['zone_id'];
			$address['country_id'] = (int)$address['country_id'];

			unset($address['custom_field']);
		}

		return $addresses;
	}

	protected function processAddress($id, $data) {
		$address = array();
		$address['address_id'] = (int)$id;
		$address['firstname'] = $data['firstname'];
		$address['lastname'] = $data['lastname'];
		$address['company'] = $data['company'];
		$address['address_1'] = $data['address_1'];
		$address['address_2'] = $data['address_2'];
		$address['postcode'] = $data['postcode'];
		$address['city'] = $data['city'];
		$address['zone_id'] = $data['zone_id'];
		$address['country_id'] = $data['country_id'];
		$address['custom_fields'] = $this->processCustomFields($data['custom_fields'], $data['address_custom_field']);

		return $address;
	}

	protected function processCustomFields($customFields, $addressCustomFields) {
		foreach($customFields as $key => &$customField) {
			if($customField['location'] != 'address') {
				// Can remove this item since it's not an account custom field.
				unset($customFields[$key]);
			}
			else {
				$customField['custom_field_id'] = (int)$customField['custom_field_id'];
				unset($customField['location']);
				unset($customField['sort_order']);
				
				if(isset($addressCustomFields[$customField['custom_field_id']])) {
					// Override the value when there the customer already filled in these fields earlier.
					$customField['value'] = $addressCustomFields[$customField['custom_field_id']];
				}
			}
		}

		return array_values($customFields);
	}

}

?>