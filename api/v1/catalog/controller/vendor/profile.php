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

        //return $this->response->setOutput({});

        //var_dump($this->request);
	}

	public function get() {
        $user = $this->user;
		$this->load->model('catalog/vdi_vendor_profile');
        $vendorProfile = $this->model_catalog_vdi_vendor_profile->getVendorProfile($user->getId());
        //var_dump($vendorProfile);

        $profile = array();

        $profile['username'] = $user->getUserName();
        $profile['vendor_id'] = $user->getVP();
        $profile['company'] = $vendorProfile['company'];
        $profile['vendor_description'] = $vendorProfile['vendor_description'];
        $profile['firstname'] = $vendorProfile['firstname'];
        $profile['lastname'] = $vendorProfile['lastname'];
        $profile['email'] = $vendorProfile['email'];
        $profile['telephone'] = $vendorProfile['telephone'];
        $profile['address_1'] = $vendorProfile['address_1'];
        $profile['address_2'] = $vendorProfile['address_2'];
        $profile['city'] = $vendorProfile['city'];
        $profile['postcode'] = $vendorProfile['postcode'];
        $profile['country_id'] = $vendorProfile['country_id'];
        $profile['zone_id'] = $vendorProfile['zone_id'];
        $profile['vendor_image'] = $vendorProfile['vendor_image'];



        //$profile[''] = $vendorProfile[''];
        //$profile[''] = $vendorProfile[''];
        //$profile[''] = $vendorProfile[''];


        return $profile;
	}

	public function post() {

	}

}
