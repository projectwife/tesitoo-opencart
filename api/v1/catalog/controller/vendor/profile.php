<?php


class ControllerVendorProfileAPI extends ApiController {

	public function index($args = array()) {
        if ($this->user->isLogged()) {
            if($this->request->isGetRequest()) {
                $profile = $this->get();
                $this->response->setOutput($profile);
            }
            else if($this->request->isPostRequest()) {
                $this->post();
            }
            else {
                throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
            }
        }
        else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_UNAUTHORIZED, ErrorCodes::ERRORCODE_USER_NOT_LOGGED_IN, "not allowed");
		}
	}

	public function get() {

        $this->load->model('catalog/vendor');
		$vendor = $this->model_catalog_vendor->getVendor($this->user->getVP());

		if(empty($vendor)) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_VENDOR_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_VENDOR_NOT_FOUND));
		}

        $this->load->model('localisation/country');
		$this->load->model('localisation/zone');
		$this->load->model('tool/image');

		$profile = array(
			'vendor_id' => (int)$vendor['vendor_id'],
			'user_id' => (int)$vendor['user_id'],
			'username' => $vendor['username'],
			'thumb' => $this->model_tool_image->resize($vendor['vendor_image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')),
			'telephone' => $vendor['telephone'],
			'company' => $vendor['company'],
			'vendor_description' => $vendor['vendor_description'],
			'email' => $vendor['email'],
			'firstname' => $vendor['firstname'],
			'lastname' => $vendor['lastname'],
			'address' => array(
				'address_1' => $vendor['address_1'],
				'address_2' => $vendor['address_2'],
				'city' => $vendor['city'],
				'postcode' => $vendor['postcode'],
				'country' => $this->model_localisation_country->getCountry($vendor['country_id']),
				'zone' => $this->model_localisation_zone->getZone($vendor['zone_id']),
			)
		);

		return $profile;
	}

	public function post() {

        $this->load->model('catalog/vendor');
		$vendor = $this->model_catalog_vendor->getVendor($this->user->getVP());

		if(empty($vendor)) {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_VENDOR_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_VENDOR_NOT_FOUND));
		}

		if (isset($this->request->post['company'])) {
			$vendor['company'] = $this->request->post['company'];
            if (!isset($this->request->post['company'])) {
                $vendor['vendor_name'] = $this->request->post['company'];
            }
		}
        if (isset($this->request->post['vendor_name'])) {
			$vendor['vendor_name'] = $this->request->post['vendor_name'];
            if (!isset($this->request->post['company'])) {
                $vendor['company'] = $this->request->post['vendor_name'];
            }
		}
		if (isset($this->request->post['vendor_description'])) {
			$vendor['vendor_description'] = $this->request->post['vendor_description'];
		}
		if (isset($this->request->post['telephone'])) {
			$vendor['telephone'] = $this->request->post['telephone'];
		}
		if (isset($this->request->post['email'])) {
			$vendor['email'] = $this->request->post['email'];
		}
		if (isset($this->request->post['firstname'])) {
			$vendor['firstname'] = $this->request->post['firstname'];
		}
		if (isset($this->request->post['lastname'])) {
			$vendor['lastname'] = $this->request->post['lastname'];
		}
		if (isset($this->request->post['address_1'])) {
			$vendor['address_1'] = $this->request->post['address_1'];
		}
		if (isset($this->request->post['address_2'])) {
			$vendor['address_2'] = $this->request->post['address_2'];
		}
		if (isset($this->request->post['city'])) {
			$vendor['city'] = $this->request->post['city'];
		}
		if (isset($this->request->post['postcode'])) {
			$vendor['postcode'] = $this->request->post['postcode'];
		}
		if (isset($this->request->post['country_id'])) {
			$vendor['country_id'] = (int)$this->request->post['country_id'];
		}
		if (isset($this->request->post['zone_id'])) {
			$vendor['zone_id'] = (int)$this->request->post['zone_id'];
		}

		$this->model_catalog_vendor->editVendor($vendor);
	}

}
