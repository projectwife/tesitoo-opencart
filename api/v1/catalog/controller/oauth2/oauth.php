<?php

class ControllerOAuth2OAuthApi extends ApiController {

	public function index($args = array()) {
		if(!$this->request->isOAuthTokenRequest() && !$this->oauth->isValid()) {
			return new ApiAction('oauth2/invalidtoken');
		}
	}
}

?>