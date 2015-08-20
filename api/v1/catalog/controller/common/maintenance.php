<?php

require_once(DIR_APPLICATION . 'controller/common/maintenance.php');

class ControllerCommonMaintenanceApi extends ControllerCommonMaintenance {

	protected function forward($route, $args = array()) {
		switch($route) {
			case 'common/maintenance/info':
				$this->response->setHttpResponseCode(ApiResponse::HTTP_RESPONSE_CODE_SERVICE_UNAVAILABLE);
				$this->response->output();
				exit();

				break;
		}
	}

}

?>