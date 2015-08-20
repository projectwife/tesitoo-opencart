<?php

class ApiResponse extends Response {
	const HTTP_RESPONSE_CODE_OK 					= 200;
	const HTTP_RESPONSE_CODE_CREATED 				= 201;

	const HTTP_RESPONSE_CODE_NOT_MODIFIED 			= 304;

	const HTTP_RESPONSE_CODE_BAD_REQUEST 			= 400;
	const HTTP_RESPONSE_CODE_UNAUTHORIZED 			= 401;
	const HTTP_RESPONSE_CODE_FORBIDDEN 				= 403;
	const HTTP_RESPONSE_CODE_NOT_FOUND 				= 404;

	const HTTP_RESPONSE_CODE_INTERNAL_SERVER_ERROR	      = 500;
      const HTTP_RESPONSE_CODE_SERVICE_UNAVAILABLE          = 503;

      private $httpResponseCodeHeader;
      private $interceptOutput = false;
      private $interceptedOutput; // Will be filled when interceptOutput = true.
      private $redirectCallback = null;

	public function __construct($registry) {
		$this->request = $registry->get('request');
	}

	public function redirect($url, $status = 302) {
            if($this->redirectCallback != null) {
                  $this->redirectCallback->redirect($url, $status);
            }
            else {
                  parent::redirect($url, $status);
            }
	}

      public function setRedirectCallback($redirectCallback) {
            $this->redirectCallback = $redirectCallback;
      }
      
	public function setHttpResponseCode($code) {
            $text = $this->getHttpStatusCodeText($code);
		$protocol = (isset($this->request->server['SERVER_PROTOCOL']) ? $this->request->server['SERVER_PROTOCOL'] : 'HTTP/1.0');

            $this->httpResponseCodeHeader = $protocol . ' ' . $code . ' ' . $text;
	}

	public function setOutput($output, $jsonDecode = true) {
            if($this->interceptOutput === true) {
                  $this->interceptedOutput = $output;
            }
            else {
                  if($jsonDecode === true) {
                        if(!empty($output)) {
                              // All data is saved to DB with HTML encoding so need to decode.
                              $this->htmlDecode($output);
                              $output = str_replace('\/', '/', json_encode($output));
                        } else {
                              $output = null;
                        }
                  }

                  parent::setOutput($output);
            }
            
	}

      public function output() {
            if (!headers_sent()) {
                  // Default content type. This may be overwritten in the superclass when the addHeader function is used to 
                  // add the Content-Type header. If the response body is empty this header will always be used because the 
                  // parent doesn't set any header when the output is empty.
                  header('Content-Type: text/plain', true);

                  if($this->httpResponseCodeHeader) {
                        // Response code header must be added here because parent output will only add headers if the output
                        // isn't empty but we do allow empty output.
                        header($this->httpResponseCodeHeader, true);
                  }
            }

            parent::output();
      }

      public function setInterceptOutput($intercept) {
            $this->interceptOutput = $intercept;
      }

      public function isInterceptOutput() {
            return $this->interceptOutput;
      }

      public function getInterceptedOutput() {
            return $this->interceptedOutput;
      }

      private function htmlDecode(&$data) {
            if(is_array($data)) {
                  foreach($data as &$record) {
                        if(is_array($record)) {
                              $this->htmlDecode($record);
                        }
                        elseif(is_string($record)) {
                              $record = html_entity_decode($record, ENT_QUOTES);
                        }
                  }
            }
            else {
                  $data = html_entity_decode($data, ENT_QUOTES);
            }
      }

	private function getHttpStatusCodeText($code) {
		switch ($code) {
            case 100: $text = 'Continue'; break;
            case 101: $text = 'Switching Protocols'; break;
            case 200: $text = 'OK'; break;
            case 201: $text = 'Created'; break;
            case 202: $text = 'Accepted'; break;
            case 203: $text = 'Non-Authoritative Information'; break;
            case 204: $text = 'No Content'; break;
            case 205: $text = 'Reset Content'; break;
            case 206: $text = 'Partial Content'; break;
            case 300: $text = 'Multiple Choices'; break;
            case 301: $text = 'Moved Permanently'; break;
            case 302: $text = 'Moved Temporarily'; break;
            case 303: $text = 'See Other'; break;
            case 304: $text = 'Not Modified'; break;
            case 305: $text = 'Use Proxy'; break;
            case 400: $text = 'Bad Request'; break;
            case 401: $text = 'Unauthorized'; break;
            case 402: $text = 'Payment Required'; break;
            case 403: $text = 'Forbidden'; break;
            case 404: $text = 'Not Found'; break;
            case 405: $text = 'Method Not Allowed'; break;
            case 406: $text = 'Not Acceptable'; break;
            case 407: $text = 'Proxy Authentication Required'; break;
            case 408: $text = 'Request Time-out'; break;
            case 409: $text = 'Conflict'; break;
            case 410: $text = 'Gone'; break;
            case 411: $text = 'Length Required'; break;
            case 412: $text = 'Precondition Failed'; break;
            case 413: $text = 'Request Entity Too Large'; break;
            case 414: $text = 'Request-URI Too Large'; break;
            case 415: $text = 'Unsupported Media Type'; break;
            case 500: $text = 'Internal Server Error'; break;
            case 501: $text = 'Not Implemented'; break;
            case 502: $text = 'Bad Gateway'; break;
            case 503: $text = 'Service Unavailable'; break;
            case 504: $text = 'Gateway Time-out'; break;
            case 505: $text = 'HTTP Version not supported'; break;
            default:
                exit('Unknown http status code "' . htmlentities($code) . '"');
            break;
        }

        return $text;
	}
}

?>