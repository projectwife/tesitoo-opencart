<?php

abstract class ApiController extends Controller {

	public function __construct($registry) {
		parent::__construct($registry);
		
		$this->response->setRedirectCallback($this);
	}

	public function redirect($url, $status = 302) {
		// Empty by default.
	}

	protected function getInternalRouteData($route, $isResultJsonEncoded = false) {
		$action = new Action($route);

		return $this->executeInternalAction($action, $isResultJsonEncoded);
	}

	protected function getInternalApiRouteData($route, $isResultJsonEncoded = false) {
		$action = new ApiAction($route);

		return $this->executeInternalAction($action, $isResultJsonEncoded);
	}

	protected function executeInternalAction($action, $isResultJsonEncoded = false) {
		$currentInterceptOutput = $this->response->isInterceptOutput();
		$this->response->setInterceptOutput(true);

		$action->execute($this->registry);

		// Restore intercept output.
		$this->response->setInterceptOutput($currentInterceptOutput);

		$data = $this->response->getInterceptedOutput();

		if($isResultJsonEncoded == true) {
			$data = json_decode($data, true);
		}

		return $data;
	}
}