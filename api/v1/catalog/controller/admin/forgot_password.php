<?php

require_once(DIR_API_APPLICATION . 'controller/admin/base/login_base.php');

class ControllerAdminForgotPasswordAPI extends ApiController {

    public function index($args = array()) {

        if($this->request->isPostRequest()) {
            $this->post();
        }
        else {
            throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
        }
    }

    /**
    * Resource methods
    */

    public function post() {
        $data = array();

        if (isset($this->request->post['username'])) {

            $this->language->load('mail/email_notification');
            $this->load->model('user/user');

            //get email
            $user = $this->model_user_user->getUserByUsername(
                                            $this->request->post['username']);
            if (!$user) {
                throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_VENDOR_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_VENDOR_NOT_FOUND));
            }

            $code = sha1(uniqid(mt_rand(), true));

            $this->model_user_user->editCode($user['email'], $code);

            /*
            //generate token
            $bytes = openssl_random_pseudo_bytes(16, $cstrong);
            $token = bin2hex($bytes);
            */

            //send email containing token
            $subject = $this->language->get('text_subject_password_reset_requested');

            $link = "https://tesitoo.com/admin/index.php?route=common/reset?"
                . "code=" . $code;

            $html = sprintf($this->language->get('text_to'), $user['firstname'] . ' ' . $user['lastname']) . "<br><br>";

            $html .= sprintf($this->language->get('text_message_password_reset_requested'), $link) . "<br>";

            $html .= $this->language->get('text_thanks') . "<br>";
            $html .= html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8') . "<br><br>";
            $html .= $this->language->get('text_system');

            $this->log->write($html);

            $mail = new Mail();
            $mail->protocol = $this->config->get('config_mail_protocol');
            $mail->parameter = $this->config->get('config_mail_parameter');
            $mail->smtp_hostname = $this->config->get('config_mail_smtp_hostname');
            $mail->smtp_username = $this->config->get('config_mail_smtp_username');
            $mail->smtp_password = html_entity_decode($this->config->get('config_mail_smtp_password'), ENT_QUOTES, 'UTF-8');
            $mail->smtp_port = $this->config->get('config_mail_smtp_port');
            $mail->smtp_timeout = $this->config->get('config_mail_smtp_timeout');

            $mail->setTo($user['email']);
            $mail->setFrom($this->config->get('config_email'));;
            $mail->setSender(html_entity_decode($this->config->get('config_name'), ENT_QUOTES, 'UTF-8'));
            $mail->setSubject(html_entity_decode($subject, ENT_QUOTES, 'UTF-8'));
            $mail->setHtml($html);
            $mail->send();
        }
        else {
            throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_BAD_PARAMETER, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_BAD_PARAMETER));
        }

        $this->response->setOutput($data);

        ApiException::evaluateErrors($data);
    }
}

?>