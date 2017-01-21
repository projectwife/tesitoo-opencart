<?php


class ControllerVendorRegisterAPI extends ApiController {
	private $error = array();

	public function index($args = array()) {

		if ($this->request->isPostRequest()) {
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

		if (!isset($data['confirm'])) {
			//we expect to handle password confirmation check on the client side
			$this->request->post['confirm'] = $this->request->post['password'];
		}
		if (!isset($this->request->post['signup_plan'])) {
			$this->request->post['signup_plan'] = '1::1:';
		}
		if (!isset($this->request->post['iban'])) {
			$this->request->post['iban'] = '';
		}
		if (!isset($this->request->post['swift_bic'])) {
			$this->request->post['swift_bic'] = '';
		}
		if (!isset($this->request->post['bank_name'])) {
			$this->request->post['bank_name'] = '';
		}
		if (!isset($this->request->post['bank_address'])) {
			$this->request->post['bank_address'] = '';
		}
		if (!isset($this->request->post['fax'])) {
			$this->request->post['fax'] = '';
		}
		if (!isset($this->request->post['paypal'])) {
			$this->request->post['paypal'] = '';
		}
		if (!isset($this->request->post['tax_id'])) {
			$this->request->post['tax_id'] = '';
		}
		if (!isset($this->request->post['store_url'])) {
			$this->request->post['store_url'] = '';
		}
		if (!isset($this->request->post['company_id'])) {
			$this->request->post['company_id'] = '';
		}
		if (!isset($this->request->post['store_description'])) {
			$this->request->post['store_description'] = '';
		}
		if (!isset($this->request->post['hsignup_plan'])) {
			$this->request->post['hsignup_plan'] = '';
		}
		if (!isset($this->request->post['telephone'])) {
			$this->request->post['telephone'] = '';
		}
		if (!isset($this->request->post['address_1'])) {
			$this->request->post['address_1'] = '';
		}
		if (!isset($this->request->post['address_2'])) {
			$this->request->post['address_2'] = '';
		}
		if (!isset($this->request->post['city'])) {
			$this->request->post['city'] = '';
		}
		if (!isset($this->request->post['postcode'])) {
			$this->request->post['postcode'] = '';
		}
		if (!isset($this->request->post['country_id'])) {
			$this->request->post['country_id'] = '';
		}
		if (!isset($this->request->post['zone_id'])) {
			$this->request->post['zone_id'] = '';
		}
		if (!isset($this->request->post['company'])) {
			$this->request->post['company'] = $data['firstname'] . ' ' . $data['lastname'];
		}

		$data = parent::getInternalRouteData('account/signup/index');

		if ('' != ($data['error_warning'])) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_BAD_PARAMETER, $data['error_warning']);
		}
		if ('' != ($data['error_username'])) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_BAD_PARAMETER, $data['error_username']);
		}
		if ('' != ($data['error_firstname'])) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_BAD_PARAMETER, $data['error_firstname']);
		}
		if ('' != ($data['error_lastname'])) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_BAD_PARAMETER, $data['error_lastname']);
		}
		if ('' != ($data['error_email'])) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_BAD_PARAMETER, $data['error_email']);
		}
		if ('' != ($data['error_paypal'])) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_BAD_PARAMETER, $data['error_paypal']);
		}
       if ('' != ($data['error_telephone'])) {
            throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_BAD_PARAMETER, $data['error_telephone']);
        }
        if ('' != ($data['error_password'])) {
            throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_BAD_PARAMETER, $data['error_password']);
        }
        if ('' != ($data['error_address_1'])) {
            throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST,     ErrorCodes::ERRORCODE_BAD_PARAMETER, $data['error_address_1']);
        }
        if ('' != ($data['error_city'])) {
            throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_BAD_PARAMETER, $data['error_city']);
        }
        if ('' != ($data['error_company'])) {
            throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_BAD_PARAMETER, $data['error_company']);
        }
        if ('' != ($data['error_postcode'])) {
            throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_BAD_PARAMETER, $data['error_postcode']);
        }
        if ('' != ($data['error_country'])) {
            throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_BAD_PARAMETER, $data['error_country']);
        }
        if ('' != ($data['error_zone'])) {
            throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_BAD_PARAMETER, $data['error_zone']);
        }
	}

}

?>

