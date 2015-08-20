<?php

class ApiException extends Exception {
	private $errors;
	private $httpResponseCode;

    public function __construct($httpResponseCode, $code = NULL, $message = NULL) {
        $this->httpResponseCode = $httpResponseCode;

        if($code != NULL && $message != NULL) {
        	$this->addError($code, $message);
        }
    }
    
    public function getErrorCode() {
        return $this->errorCode;
    }

    public function getErrorMessage() {
        return $this->errorMessage;
    }

    public function getHttpResponseCode() {
        return $this->httpResponseCode;
    }

    public function addError($code, $message) {
		$error = array('code' => $code, 'message' => $message);
		$this->errors[] = $error;
	}

	public function getErrors() {
		return $this->errors;
	}

    public function containsErrors() {
        return count($this->errors) != 0;
    }

    /**
     * Method to find errors in the given data.
     * @param  array   $data           The data to search for errors.
     * @param  boolean $warningIsError Whether warnings needs to be treated as errors.
     */
    public static function evaluateErrors($data, $warningIsError = true) {
        $apiException = new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST);
        foreach($data as $key => $value) {
            if(substr($key, 0, 6) == 'error_' && !empty($value) && !($warningIsError === false && $key == 'error_warning')) {
                // Check if it's an array, this is possible in the case of custom field errors.
                if(is_array($value) && !empty($value)) {
                    foreach($value as $message) {
                        $apiException->addError($key, $message);
                    }
                }
                else {
                    $apiException->addError($key, $value);
                }
            }

            // json responses (ie add product to cart) contain error keys so check for them too
            if($key == 'error') {
                ApiException::findErrors($value, 'error', $apiException, $warningIsError);
            }
        }
        
        if($apiException->containsErrors()) {
            throw $apiException;
        }
    }

    private static function findErrors($error, $keyPrefix, $apiExceptionObject, $warningIsError = true) {
        if(is_array($error)) {
            foreach($error as $errorKey => $errorValue) {
                if(!($warningIsError === false && $errorKey == 'warning')) {
                    $key = $keyPrefix . '_' . $errorKey;
                    ApiException::findErrors($errorValue, $key, $apiExceptionObject, $warningIsError);
                }
            }
        }
        else {
            $apiExceptionObject->addError($keyPrefix, $error);
        }
    }
}

?>