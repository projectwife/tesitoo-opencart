<?php


class ControllerVendorRegisterAPI extends ApiController {
	private $error = array();

	public function index($args = array()) {

		if ($this->request->isPostRequest() && $this->validate()) {
			$this->handleRegistration();
		}
	}

	public function handleRegistration() {

		$this->load->model('account/signup');
		$data = array();

		$data['username'] = $this->request->post['username'];
		$data['password'] = $this->request->post['password'];
		$data['firstname'] = $this->request->post['firstname'];
		$data['lastname'] = $this->request->post['lastname'];
		$data['email'] = $this->request->post['email'];
		$data['telephone'] = $this->request->post['telephone'];
		$data['address_1'] = $this->request->post['address_1'];
		$data['address_2'] = $this->request->post['address_2'];
		$data['city'] = $this->request->post['city'];
		$data['postcode'] = $this->request->post['postcode'];
		$data['country_id'] = $this->request->post['country_id'];
		$data['zone_id'] = $this->request->post['zone_id'];
		$data['company'] = $this->request->post['company'];

		$this->request->post['singup_plan'] /*sic*/ = '1::1:';
		$data['iban'] = '';
		$data['swift_bic'] = '';
		$data['bank_name'] = '';
		$data['bank_address'] = '';
		$data['fax'] = '';
		$data['paypal'] = '';
		$data['tax_id'] = '';
		$data['store_url'] = '';
		$data['company_id'] = '';
		$data['store_description'] = '';
		$this->request->post['hsignup_plan'] = '';

		//echo($this->model_account_signup);
		//echo("----------------------");

		$this->model_account_signup->addVendorSignUp($data);
	}


	public function validate() {

		//TODO define error_username
		//TODO define error_company

		//TODO how to avoid duplicate sign-ups: username, email, company?

		if ((utf8_strlen($this->request->post['username']) < 1) || (utf8_strlen($this->request->post['username']) > 32)) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_INVALID_USERNAME, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_INVALID_USERNAME));
		}

		if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_INVALID_PASSWORD, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_INVALID_PASSWORD));
		}

		if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_INVALID_FIRSTNAME, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_INVALID_FIRSTNAME));
		}

		if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_INVALID_LASTNAME, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_INVALID_LASTNAME));
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || !preg_match('/^[^\@]+@.*.[a-z]{2,15}$/i', $this->request->post['email'])) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_INVALID_EMAIL, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_INVALID_EMAIL));
		}

		/*
		if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
			$data['error_warning'] = $this->language->get('error_exists');
		}
		*/

		if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_INVALID_TELEPHONE, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_INVALID_TELEPHONE));
		}

		if ((utf8_strlen(trim($this->request->post['address_1'])) < 3) || (utf8_strlen(trim($this->request->post['address_1'])) > 128)) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_INVALID_ADDRESS, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_INVALID_ADDRESS));
		}

		if ((utf8_strlen(trim($this->request->post['city'])) < 2) || (utf8_strlen(trim($this->request->post['city'])) > 128)) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_INVALID_CITY, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_INVALID_CITY));
		}

		if ((utf8_strlen(trim($this->request->post['postcode'])) < 2 || utf8_strlen(trim($this->request->post['postcode'])) > 10)) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_INVALID_POSTCODE, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_INVALID_POSTCODE));
		}

		if ($this->request->post['country_id'] == '') {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_INVALID_COUNTRY, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_INVALID_COUNTRY));
		}

		$this->load->model('localisation/country');

		$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);

		if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_INVALID_ZONE, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_INVALID_ZONE));
		}

		if ((utf8_strlen($this->request->post['company']) < 1) || (utf8_strlen($this->request->post['company']) > 32)) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_INVALID_COMPANY, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_INVALID_COMPANY));
		}

		return true;
	}

}
