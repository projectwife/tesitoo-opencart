<?php
class ControllerPaymentStripePro extends Controller {
    private $meta = array(
        'ext_id'   => 'stripepro',
        'type'     => 'payment',
        'route'    => 'payment/',
        'stripe_path' => './system/library/stripe-php-7.46.1/init.php',
        'mode'     => 'demo1',
    );

    public function __construct($registry) {
        parent::__construct($registry);
        $this->registry = $registry;
        $this->bird = new DBI\Bird($registry, $this->meta);
        $this->dbi = ($dbi = $this->registry->get('dbi_catalog')) ? $dbi : new DBI\Catalog($this->registry, $this->meta);
        $this->full_route = $this->meta['route'] . $this->meta['ext_id'];
        $this->espro = ($espro = $this->registry->get('dbi_esp_front')) ? $espro : new ESP\Front($registry, $this->dbi, $this->meta);
    }
    private function bprint($value) {
        print('<pre>');
        print_r($value);
        print('</pre>');
    }
    private function getConfig($key) {
        return $this->dbi->getConfig($key,  $this->meta['ext_id'], $this->meta['type']);
    }

    private function random_int($min, $max) {
        if (version_compare(PHP_VERSION, '7.0.0', '<') && !function_exists('random_int')) {
            mt_srand();
            return mt_rand($min, $max);
        } else {
            return random_int($min, $max);
        }
    }

    private function tabActiveClass($name) {
        return $this->getConfig('default_payment_method') == $name ? 'active' : '';
    }

    public function index() {
        $this->load->language($this->full_route);
        //~ lang
        $data = array();
        $this->loadLangVars($data);
        //~

        // || Unpack
        // submit button text (payment form)
        $submit_button_text = $this->getConfig('button_text');
        if(!$submit_button_text) {
            $submit_button_text = $this->language->get('text_submit_payment');
        }
        // checkout button text (checkout page)
        $checkout_button_text = $this->getConfig('button_text');
        if(!$checkout_button_text) {
            $checkout_button_text = $this->language->get('text_checkout');
        }
        // css classes
        $button_css_classes  = $this->getConfig('button_css_classes');
        if(!$button_css_classes) {
            $button_css_classes = 'btn btn-primary';
        }
        // public key
        $publicKey = $this->getConfig('transaction_mode') ? $this->getConfig('live_public') : $this->getConfig('test_public');
        $data['text_checkout']              = $checkout_button_text;
        $data['text_submit_payment']        = $submit_button_text;
        $data['button_css_classes']         = $button_css_classes;
        $data['public_key']                 = $publicKey;
        $data['transaction_mode']           = $this->getConfig('transaction_mode');
        $data['paymenttype_creditcards']    = $this->getConfig('paymenttype_creditcards');
        $data['card_template']              = $this->getConfig('card_template');
        $data['checkout_type']              = $this->getConfig('checkout_type');
        $data['paymenttype_button']         = $this->getConfig('paymenttype_button');
        $data['paymenttype_bancontact']     = $this->getConfig('paymenttype_bancontact');
        $data['paymenttype_giropay']        = $this->getConfig('paymenttype_giropay');
        $data['paymenttype_ideal']          = $this->getConfig('paymenttype_ideal');
        $data['paymenttype_alipay']         = $this->getConfig('paymenttype_alipay');
        $data['paymenttype_wechat']         = $this->getConfig('paymenttype_wechat');
        $data['paymenttype_eps']            = $this->getConfig('paymenttype_eps');
        $data['paymenttype_p24']            = $this->getConfig('paymenttype_p24');
        $data['paymenttype_multibanco']     = $this->getConfig('paymenttype_multibanco');
        $data['paymenttype_klarna']         = $this->getConfig('paymenttype_klarna');
        $data['paymenttype_fpx']            = $this->getConfig('paymenttype_fpx');
        $data['button_styles']              = $this->getConfig('button_styles');
        $data['custom_css']                 = $this->getConfig('custom_css');

        $data['storedcard_tab']     = $this->tabActiveClass('storedcard');
        $data['creditcards_tab']    = $this->tabActiveClass('creditcards');
        $data['alipay_tab']         = $this->tabActiveClass('alipay');
        $data['wechat_tab']         = $this->tabActiveClass('wechat');
        $data['bancontact_tab']     = $this->tabActiveClass('bancontact');
        $data['giropay_tab']        = $this->tabActiveClass('giropay');
        $data['ideal_tab']          = $this->tabActiveClass('ideal');
        $data['eps_tab']            = $this->tabActiveClass('eps');
        $data['p24_tab']            = $this->tabActiveClass('p24');
        $data['multibanco_tab']     = $this->tabActiveClass('multibanco');
        $data['klarna_tab']         = $this->tabActiveClass('klarna');
        $data['fpx_tab']            = $this->tabActiveClass('fpx');
        $data['mode']               = $this->meta['mode'];
        $data['url_base']           = 'index.php?route=' . $this->meta['route'] . $this->meta['ext_id'];
        $data['success_url']        = $this->url->link('checkout/success', '', 'SSL');

        // ||
        $b = $this->request->server['HTTPS'] ? HTTPS_SERVER : HTTP_SERVER;
        $data['b'] = $b;
        $ver = $this->language->get('version');
        $cur = $this->getConfig('license_key');

        // || verify
        $json = $this->bird->verify($b, $ver, $cur);
        if (!$json->{'success'}) {
            return $this->dbi->load_view($this->full_route . 'error', $data); // extension/payment/stripeproerror
        }

        // || post739
        $seed = $this->random_int(2, 100);
        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $sessiondata = $this->espro->post739($b, $ver, $cur, $seed, $order_info);
        if (!$sessiondata->{'success'}) {
            return $this->dbi->load_view($this->full_route . 'error', $data); // extension/payment/stripeproerror
        }
        $d2 = [];
        $d3 = $sessiondata->{'d3'};
        $d4 = $sessiondata->{'d4'};
        $d9 = round($sessiondata->{'d9'} / $seed);
        $d13 = $this->espro->decS($sessiondata->{'d13'}, $seed);
        $d19 = $sessiondata->{'d19'};
        array_push($d2, [
            'name' => 'Total',
            'amount' => $d9,
            'currency' => $d13,
            'quantity' => 1,
        ]);

        // || PM Wechat & Alipay
        $res = $this->espro->indexWechatAlipay($d9);
        $data = array_merge($data, $res);

        // || FPX
        if($this->getConfig('paymenttype_fpx')) {
            $data['fpx_amount']             = $this->currency->format((float)$d9/100, 'MYR', '', false) * 100;
            $data['fpx_formatted_amount']   = $this->currency->format((float)$d9/100, 'MYR');
        }

        // || Generate billing info
        $this->load->model('localisation/country');
        $country_info = $this->model_localisation_country->getCountry($order_info['payment_country_id']);
        $data['billing'] = array(
            'email'             => $order_info['email'],
            'name'              => $order_info['payment_firstname'] . ' ' . $order_info['payment_lastname'],
            'address' => array(
                'line1'          => $order_info['payment_address_1'],
                'line2'          => $order_info['payment_address_2'],
                'city'          => $order_info['payment_city'],
                'state'          => $order_info['payment_zone'],
                'postal_code'   => $order_info['payment_postcode'],
                'country'       => isset($country_info['iso_code_2']) ? $country_info['iso_code_2'] : ''
            )
        );

        // || PM Klarna
        $res = $this->espro->indexKlarna($d9, isset($country_info['iso_code_2']) ? $country_info['iso_code_2'] : '');
        $data = array_merge($data, $res);


        // || hasEur
        $this->load->model('localisation/currency');
        $currencies = $this->model_localisation_currency->getCurrencies();
        $hasEur = false;
        foreach ($currencies as $currency) {
            if($currency['code'] == 'EUR') {
                $hasEur = true;
            }
        }


        // ||
        if(strtoupper($d13) == 'JPY') {
            $amount = $this->currency->format((float)$d9/100, strtoupper($d13), '', false);
        } else {
            $amount = $this->currency->format((float)$d9/100, strtoupper($d13), '', false) * 100;
        }
        $data['amount']             = $amount;
        $data['amount_eur']         = $this->currency->format((float)$d9/100, $hasEur ? 'EUR' : strtoupper($d13), '', false) * 100;
        $data['amount_formatted']   = $this->currency->format((float)$d9/100, strtoupper($d13));
        $data['currency']           = $d13;
        $data['d19']                = $d19;
        $data['first_name']         = $order_info['payment_firstname'];
        $data['last_name']          = $order_info['payment_lastname'];
        $data['order_id']           = $order_info['order_id'];
        $data['firstname']          = $order_info['firstname'];
        $data['lastname']           = $order_info['lastname'];
        $data['email']              = $order_info['email'];
        $data['session_id']         = 0;


        // ||
        if($this->getConfig('charge_mode') == 1) {
            $capture_method = 'automatic';
        } else {
            $capture_method = 'manual';
        }


        // ||
        if ($data['checkout_type'] == 'checkout_page') {
            // skip -> make dynamic call later
        } else {
            $transactionDescr = $this->espro->formatDescription($order_info);
            $privateKey = $this->getConfig('transaction_mode') ? $this->getConfig('live_private') : $this->getConfig('test_private');
            require_once($this->meta['stripe_path']);
            \Stripe\Stripe::setApiKey($privateKey);
            // payment intent
            $intent = \Stripe\PaymentIntent::create([
                "amount"                => $data['amount'],
                "currency"              => $d13,
                "setup_future_usage"    => "off_session",
                "payment_method_types"  => ["card"],
                "capture_method"        => $capture_method,
                "description"           => $transactionDescr,
                "metadata" => [
                    "order_id"  => $order_info['order_id'],
                    "firstname" => $order_info['firstname'],
                    "lastname"  => $order_info['lastname'],
                    "email"     => $order_info['email'],
                ]
            ]);
            $data['intent'] = $intent;
        }


        // || Customer stored cards
        $data['use_stored_cards'] = $this->getConfig('use_stored_cards');
        if($data['use_stored_cards']) {
            $data['storedcards'] = $this->espro->indexStoredCards();
        }

        return $this->dbi->load_view($this->full_route, $data);
    }


    public function sessionPost() {
        $this->response->addHeader('Content-Type: application/json');
        $json = $this->espro->sessionPost();
        $this->response->setOutput($json);
    }

    public function intentPost() {
        $pi = $_POST['paymentIntent'];
        $this->response->addHeader('Content-Type: application/json');
        $json = $this->espro->updateIntent($pi);
        $this->response->setOutput($json);
    }

    public function send() {
        $this->response->addHeader('Content-Type: application/json');
        $pm = $_POST['paymentMethod'];

        $json = $this->espro->send($pm);
        $this->response->setOutput($json);
    }

    public function checkoutWebhook() {
        $this->load->model('checkout/order');
        $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->getConfig('order_status_id'));
        header('Location: ' . $this->url->link('checkout/success', '', 'SSL'));
    }

    private function parseGet($var, $get) {
        return isset($get[$var]) ? $get[$var] : Null;
    }
    public function finishLocal() {
        /*
        [client_secret] => src_client_secret_fj...
        [livemode] => false
        [route] => extension/payment/stripepro/finishLocal
        [source] => src_sd...
        [redirect_status] => succeeded | failed
       */
        $client_secret    = $this->parseGet('client_secret', $_GET);
        $livemode         = $this->parseGet('livemode', $_GET);
        $source_id        = $this->parseGet('source', $_GET);
        $redirect_status  = $this->parseGet('redirect_status', $_GET);

        $isSuccess = $this->espro->finishLocal($client_secret, $livemode, $source_id, $redirect_status);
        if(!$isSuccess) {
            header('Location: ' . $this->url->link('checkout/failure', '', 'SSL'));
        } else {
            header('Location: ' . $this->url->link('checkout/success', '', 'SSL'));
        }
    }

    public function wechatWebhook() {
        $this->espro->wechatWebhook();
    }

    public function checkStripeSource() {
        $sourceId = $_GET['sourceId'];
        $mode = $_GET['mode']; // live | test
        if(!$sourceId || !$mode) {
            return $this->response->setOutput(json_encode(['error' => True]));
        }
        $json = $this->espro->checkStripeSource($mode, $sourceId);
        return $this->response->setOutput($json);
    }

    public function createFPXintent() {
        $pi = $this->espro->createFPXintent();
        return $this->response->setOutput($pi);
    }

    public function finishFPX() {
        /*
        [payment_intent] => pi_1...
        [payment_intent_client_secret] => pi_1...
        [redirect_status] => succeeded
       */
        $payment_intent                 = $this->parseGet('payment_intent', $_GET);
        $payment_intent_client_secret   = $this->parseGet('payment_intent_client_secret', $_GET);
        $redirect_status                = $this->parseGet('redirect_status', $_GET);
        $isSuccess = $this->espro->finishFPX($payment_intent);
        if(!$isSuccess) {
            header('Location: ' . $this->url->link('checkout/failure', '', 'SSL'));
        } else {
            header('Location: ' . $this->url->link('checkout/success', '', 'SSL'));
        }
    }

    function multibancoPendingOrder() {
        $post = $_POST['post'];
        if($post != 1) {
            return $this->response->setOutput(-1);
        }
        $this->espro->multibancoPendingOrder();
        return $this->response->setOutput(1);
    }
    public function multibancoWebhook() {
        // [oc3] example.com/index.php?route=extension/payment/stripepro/multibancoWebhook
        $this->espro->multibancoWebhook();
    }

    private function loadLangVars(&$data) {
        $data['text_credit_card']             = $this->language->get('text_credit_card');
        $data['button_confirm']               = $this->language->get('button_confirm');
        $data['button_back']                  = $this->language->get('button_back');
        $data['text_enter_card_detail']       = $this->language->get('text_enter_card_detail');
        $data['text_or_enter_card_detail']    = $this->language->get('text_or_enter_card_detail');
        $data['text_newcard']                 = $this->language->get('text_newcard');
        $data['text_card_number']             = $this->language->get('text_card_number');
        $data['text_expiration']              = $this->language->get('text_expiration');
        $data['text_cvc']                     = $this->language->get('text_cvc');
        $data['text_storedcard']              = $this->language->get('text_storedcard');
        $data['text_loading']                 = $this->language->get('text_loading');
        $data['text_save_card']               = $this->language->get('text_save_card');
        $data['version']                      = $this->language->get('version');
        $data['msg_wechat']                   = $this->language->get('msg_wechat');
    }
}
