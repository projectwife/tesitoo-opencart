<?php

class ControllerCommonInitBaseAPI extends ApiController {

    const INCLUDE_LANGUAGES_KEY         = 'languages';
    const INCLUDE_CURRENCIES_KEY        = 'currencies';
    const INCLUDE_COUNTRIES_KEY         = 'countries';
    const INCLUDE_SETTINGS_KEY          = 'settings';
    const INCLUDE_CUSTOMER_GROUPS_KEY   = 'customer_groups';
    const INCLUDE_CART_KEY              = 'cart';
    const INCLUDE_WISHLIST_KEY          = 'wishlist';

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

    public function get() {
        $include = null;
        $includes = array();

        if(isset($this->request->get['include'])) {
            $include = $this->request->get['include'];
            $includes = explode(',', $include);
        }

        $output = array();

        if($include == null || in_array(self::INCLUDE_LANGUAGES_KEY, $includes)) {
            $languages = parent::getInternalApiRouteData('common/language');
            $output = array_merge($output, $languages);
        }

        if($include == null || in_array(self::INCLUDE_CURRENCIES_KEY, $includes)) {
            $currencies = parent::getInternalApiRouteData('common/currency');
            $output = array_merge($output, $currencies);
        }

        if($include == null || in_array(self::INCLUDE_COUNTRIES_KEY, $includes)) {
            $countries = parent::getInternalApiRouteData('common/country');
            $output = array_merge($output, $countries);
        }

        if($include == null || in_array(self::INCLUDE_SETTINGS_KEY, $includes)) {
            $settings = parent::getInternalApiRouteData('common/settings');
            $output = array_merge($output, $settings);
        }

        if($include == null || in_array(self::INCLUDE_CUSTOMER_GROUPS_KEY, $includes)) {
            $customerGroups = parent::getInternalApiRouteData('common/customer_group');
            $output = array_merge($output, $customerGroups);
        }

        if($include == null || in_array(self::INCLUDE_CART_KEY, $includes)) {
            $cart = parent::getInternalApiRouteData('cart/cart');
            $output = array_merge($output, $cart);
        }

        if($include == null || in_array(self::INCLUDE_WISHLIST_KEY, $includes)) {
            $wishlist['wishlist'] = null;

            if($this->customer->isLogged()) {
                $wishlist = parent::getInternalApiRouteData('account/wishlist');
            }
            $output = array_merge($output, $wishlist);
        }

        $this->response->setOutput($output);
    }

}

?>