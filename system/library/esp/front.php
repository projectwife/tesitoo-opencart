<?php

namespace ESP;
final class Front {
    private $registry;
    public function __construct($registry, $dbi, $meta) {
        $this->registry = $registry;
        $this->config = $registry->get('config');
        $this->registry->set('dbi_esp_front', $this);
        $this->dbi = $dbi;
        $this->meta = $meta;
    }
    public function __get($name) {
        return $this->registry->get($name);
    }

    private function bprint($value) {
        print('<pre>');
        print_r($value);
        print('</pre>');
    }
    private function getConfig($key) {
        return $this->dbi->getConfig($key,  $this->meta['ext_id'], $this->meta['type']);
    }
    // utils
    private function random_int($min, $max) {
        if (version_compare(PHP_VERSION, '7.0.0', '<') && !function_exists('random_int')) {
            mt_srand();
            return mt_rand($min, $max);
        } else {
            return random_int($min, $max);
        }
    }
    // TODO make private
    public function hash($str, $s) {
        $res = '';
        $array = str_split($str);
        foreach ($array as $c) {
            $res .= (ord($c) - $s) . '*';
        }
        return $res;
    }
    // TODO make private
    public function decS($str, $s) {
        $res = '';
        $array = explode('*', $str);
        foreach ($array as $c) {
            if($c != '') {
                $res .= chr($c + $s);
            }
        }
        return $res;
    }
    // todo make private
    public function getStripeCustomer() {
        require_once($this->meta['stripe_path']);
        if($this->getConfig('transaction_mode')) {
            \Stripe\Stripe::setApiKey($this->getConfig('live_private'));
        } else {
            \Stripe\Stripe::setApiKey($this->getConfig('test_private'));
        }

        $stripe_customer = null;
        if (isset($this->session->data['customer_id'])) {
            $oc_user_id = $this->session->data['customer_id'];
            $this->load->model('account/customer');
            $customer_info = $this->model_account_customer->getCustomer($oc_user_id);
            $customer_email = $customer_info['email'];
            if($customer_email) {
                // stripe customer
                $stripe_resp = \Stripe\Customer::all(["limit" => 1, "email" => $customer_email]);
                $stripe_customers = $stripe_resp->data;
                if(count($stripe_customers) > 0) {
                    $stripe_customer = $stripe_customers[0];
                }
            }
        }
        return $stripe_customer;
    }
    public function updateIntent($pi) {
        $json = array();
        require_once($this->meta['stripe_path']);
        if($this->getConfig('transaction_mode')) {
            \Stripe\Stripe::setApiKey($this->getConfig('live_private'));
        } else {
            \Stripe\Stripe::setApiKey($this->getConfig('test_private'));
        }

        $stripe_customer = $this->getStripeCustomer();
        $json['intent'] = null;
        if($stripe_customer) {
            $intent = \Stripe\PaymentIntent::update(
                $pi,
                ['customer' => $stripe_customer['id']]
            );
            $json['intent'] = $intent;
        }
        return json_encode($json);
    }
    public function formatDescription($order_info) {
        $transaction_descr_format = $this->getConfig('transaction_description');
        if($transaction_descr_format) {
            $transaction_descr = $transaction_descr_format;
            $transaction_descr = str_replace('{email}', $order_info['email'], $transaction_descr);
            $transaction_descr = str_replace('{fullname}', $order_info['firstname'] . ' ' . $order_info['lastname'], $transaction_descr);
            $transaction_descr = str_replace('{total}', sprintf('%0.2f', $order_info['total']), $transaction_descr);
            $transaction_descr = str_replace('{currency}', $order_info['currency_code'], $transaction_descr);
            $transaction_descr = str_replace('{order_id}', $order_info['order_id'], $transaction_descr);
        } else {
            $transaction_descr = "Order #" . $order_info['order_id'];
        }
        return $transaction_descr;
    }


    //// main
    public function post739($b, $ver, $cur, $seed, $order_info) {
        $this->load->model('tool/image');
        $this->config->get('config_customer_price');

        $products = $this->cart->getProducts();
        $productsFormatted = [];
        foreach ($products as &$prod) {
            $unit_price = $this->tax->calculate($prod['price'], $prod['tax_class_id'], $this->config->get('config_tax'));
            $el['name'] = $this->hash($prod['name'], $seed);
            $el['image'] = $prod['image'];
            $el['quantity'] = $prod['quantity'] * $seed;
            $el['unit_price'] = $unit_price * $seed;
            $el['currency_value'] = $order_info['currency_value'] * $seed;
            $productsFormatted[] = $el;
        }
        unset($prod);

        $dd = array(
            'key' => $cur,
            'extension' => 'oc_stripe_pro',
            'v' => $ver,
            'ocv' => VERSION,
            'b' => $b,
            't' => $order_info['total'] * $seed,
            'c' => $this->hash($order_info['currency_code'], $seed),
            'd' => json_encode(array_values($productsFormatted)),
        );
        $url = 'https://license.stripe-opencart.com/post739';
        $ch = curl_init($url);
        $postString = http_build_query($dd, '', '&');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postString);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response);
    }

    public function indexWechatAlipay($d9) {
        $alipay_currency = $this->getConfig('alipay_currency');
        $wechat_currency = $this->getConfig('wechat_currency');
        return array(
            'amount_formatted_alipay'   => $this->currency->format((float)$d9/100, strtoupper($alipay_currency)),
            'amount_formatted_wechat'   => $this->currency->format((float)$d9/100, strtoupper($wechat_currency)),
            'alipay_currency'           => $alipay_currency,
            'wechat_currency'           => $wechat_currency,
            'alipay_amount'             => $this->currency->format((float)$d9/100, strtoupper($alipay_currency), '', false) * 100,
            'wechat_amount'             => $this->currency->format((float)$d9/100, strtoupper($wechat_currency), '', false) * 100,
        );
    }
    public function indexStoredCards() {
        $storedCards = [];
        $stripe_customer = $this->getStripeCustomer();
        if ($stripe_customer) {
            $cards = \Stripe\PaymentMethod::all([
                'customer' => $stripe_customer['id'],
                'type' => 'card',
            ]);
            foreach($cards as $source) {
                if($source->object == 'source' || $source->object == 'payment_method') {
                    $storedCards[] = [
                        'id' => $source->id,
                        'exp_month' => sprintf('%02d', $source->card->exp_month),
                        'exp_year' => $source->card->exp_year,
                        'last4' => $source->card->last4,
                        'brand' => $source->card->brand,
                    ];
                } elseif ($source->object == 'card') {
                    $storedCards[] = [
                        'id' => $source->id,
                        'exp_month' => $source->exp_month,
                        'exp_year' => $source->exp_year,
                        'last4' => $source->last4,
                        'brand' => $source->brand,
                    ];
                }
            }
        }
        return $storedCards;
    }
    public function indexKlarna($d9, $isoCountryCode) {
        $res = [];
        if ($this->getConfig('paymenttype_klarna')) {
            $klarna_cur = 'eur';
            if($isoCountryCode == 'DK') {
                $klarna_cur = 'dkk';
            } else if($isoCountryCode == 'NO') {
                $klarna_cur = 'nok';
            } else if($isoCountryCode == 'SE') {
                $klarna_cur = 'sek';
            } else if($isoCountryCode == 'GB') {
                $klarna_cur = 'gbp';
            }
            $res['klarna_cur']                 = $klarna_cur;
            $res['amount_klarna']              = $this->currency->format((float)$d9/100, strtoupper($klarna_cur), '', false) * 100;
            $res['amount_klarna_formatted']    = $this->currency->format((float)$d9/100, strtoupper($klarna_cur));
        } else {
            $res['klarna_cur']                 = 'eur';
            $res['amount_klarna']              = 0.0;
            $res['amount_klarna_formatted']    = '0.0';
        }
        return $res;
    }

    public function sessionPost() {
        $json = array();
        $this->load->language($this->full_route);
        $cur  = $this->getConfig('license_key');
        $ver  = $this->language->get('version');
        $b    = $this->request->server['HTTPS'] ? HTTPS_SERVER : HTTP_SERVER;

        // order info
        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $this->load->model('tool/image');
        $this->config->get('config_customer_price');

        $seed = $this->random_int(2, 100);
        $sessiondata = $this->post739($b, $ver, $cur, $seed, $order_info);
        if (!$sessiondata->{'success'}) {
            $json['error']      = true;
            return json_encode($json);
        }

        $d2 = [];
        $d3 = $sessiondata->{'d3'};
        $d4 = $sessiondata->{'d4'};
        $d9 = round($sessiondata->{'d9'} / $seed);
        $d13 = $this->decS($sessiondata->{'d13'}, $seed);

        if(strtoupper($d13) == 'JPY') {
            $amount = $this->currency->format((float)$d9/100, strtoupper($d13), '', false);
        } else {
            $amount = $this->currency->format((float)$d9/100, strtoupper($d13), '', false) * 100;
        }

        array_push($d2, [
            'name' => 'Total',
            'amount' => $amount,
            'currency' => $d13,
            'quantity' => 1,
        ]);

        // init stripe
        require_once($this->meta['stripe_path']);
        if($this->getConfig('transaction_mode')) {
            \Stripe\Stripe::setApiKey($this->getConfig('live_private'));
        } else {
            \Stripe\Stripe::setApiKey($this->getConfig('test_private'));
        }

        if($this->getConfig('charge_mode') == 1) {
            $capture_method = 'automatic';
        } else {
            $capture_method = 'manual';
        }

        $transaction_descr = $this->formatDescription($order_info);

        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => $d2,
            'success_url' => $d3,
            'cancel_url' => $d4,
            'customer_email'      => $order_info['email'],
            'payment_intent_data' => [
                'capture_method'      => $capture_method,
                'description'         => $transaction_descr,
                "setup_future_usage"  => "off_session",
                'metadata' => [
                    'order_id'  => $order_info['order_id'],
                    'firstname' => $order_info['firstname'],
                    'lastname'  => $order_info['lastname'],
                    'email'     => $order_info['email'],
                ]
            ]
        ]);
        $json['session_id'] = $session->id;
        $json['error']      = false;

        return json_encode($json);
    }

    public function checkStripeSource($mode, $sourceId) {
        $json = [];
        require_once($this->meta['stripe_path']);
        if($mode == 'live') {
            \Stripe\Stripe::setApiKey($this->getConfig('live_private'));
        } else {
            \Stripe\Stripe::setApiKey($this->getConfig('test_private'));
        }
        $source = \Stripe\Source::retrieve($sourceId);

//      $json['sourceId'] = $sourceId;
//      $json['mode'] = $mode;
        $json['status'] = $source['status'];
        return json_encode($json);
    }

    public function wechatWebhook() {
        $payload = @file_get_contents('php://input');
        $event = json_decode($payload);
        require_once($this->meta['stripe_path']);

        if($event->type == 'source.chargeable' && $event->data->object->type == 'wechat') {
            if($event->livemode == 'true') {
                \Stripe\Stripe::setApiKey($this->getConfig('live_private'));
            } else {
                \Stripe\Stripe::setApiKey($this->getConfig('test_private'));
            }

            $source = \Stripe\Source::retrieve($event->data->object->id);

            $charge = \Stripe\Charge::create([
                'amount' => $source['amount'],
                'currency' => $source['currency'],
                'source' => $source['id'],
            ]);

        }
    }

    public function finishLocal($client_secret, $livemode, $source_id, $redirect_status) {
        if ( $client_secret == Null || $livemode == Null || $source_id == Null || $redirect_status == Null ) {
            return false;
        }
        if ( $redirect_status === 'failed') {
            return false;
        }

        require_once($this->meta['stripe_path']);
        if($livemode == 'true') {
            \Stripe\Stripe::setApiKey($this->getConfig('live_private'));
        } else {
            \Stripe\Stripe::setApiKey($this->getConfig('test_private'));
        }

        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

        $source = \Stripe\Source::retrieve($source_id);

        if($source['status'] != 'chargeable') {
            return false;
        }
        $transaction_descr = $this->formatDescription($order_info);
        $charge = \Stripe\Charge::create([
            'amount' => $source['amount'],
            'currency' => $source['currency'],
            'source' => $source_id,
            'description' => $transaction_descr,
        ]);

        if(VERSION >= '2.0.0.0') {
            $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->getConfig('order_status_id'));
        } else {
            $this->model_checkout_order->confirm($this->session->data['order_id'], $this->getConfig('order_status_id'));
        }
        return true;
    }

    public function finishFPX($payment_intent) {
        if ($payment_intent == Null) {
            return false;
        }
        require_once($this->meta['stripe_path']);
        if($this->getConfig('transaction_mode')) {
            \Stripe\Stripe::setApiKey($this->getConfig('live_private'));
        } else {
            \Stripe\Stripe::setApiKey($this->getConfig('test_private'));
        }
        $intent = \Stripe\PaymentIntent::retrieve($payment_intent);
        if($intent['status'] != 'succeeded') {
            return false;
        }
        $this->load->model('checkout/order');
        if(VERSION >= '2.0.0.0') {
            $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->getConfig('order_status_id'));
        } else {
            $this->model_checkout_order->confirm($this->session->data['order_id'], $this->getConfig('order_status_id'));
        }
        return true;
    }

    public function send($pm) {
        $json = array();
        // Save order in OC
        $this->load->model('checkout/order');
        if(VERSION >= '2.0.0.0') {
            $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->getConfig('order_status_id'));
        } else {
            $this->model_checkout_order->confirm($this->session->data['order_id'], $this->getConfig('order_status_id'));
        }
        $json['success'] = $this->url->link('checkout/success', '', 'SSL');

        if (isset($this->session->data['customer_id'])) {
            $oc_user_id = $this->session->data['customer_id'];
        } else {
            return json_encode($json);
        }

        // oc customer
        $this->load->model('account/customer');
        $customer_info = $this->model_account_customer->getCustomer($oc_user_id);
        $customer_email = $customer_info['email'];
        if(!$customer_email) {
            return json_encode($json);
        }

        // init stripe
        require_once($this->meta['stripe_path']);
        if($this->getConfig('transaction_mode')) {
            \Stripe\Stripe::setApiKey($this->getConfig('live_private'));
        } else {
            \Stripe\Stripe::setApiKey($this->getConfig('test_private'));
        }

        // stripe customer
        $stripe_resp = \Stripe\Customer::all(["limit" => 1, "email" => $customer_email]);
        $stripe_customers = $stripe_resp->data;

        if(count($stripe_customers) > 0) {
            $stripe_customer = $stripe_customers[0];
            $stripe_customer_id = $stripe_customer['id'];
        } else {
            $stripe_customer = \Stripe\Customer::create([
                "email" => $customer_email,
                "name" => $customer_info['firstname'] . " " . $customer_info['lastname'],
                "metadata" => [
                    "opencart_customer_id" => $customer_info['customer_id'],
                ],
            ]);
            $stripe_customer_id = $stripe_customer['id'];
        }

        if($pm) {
            $payment_method = \Stripe\PaymentMethod::retrieve($pm);
            $payment_method->attach(['customer' => $stripe_customer_id]);
        }

        return json_encode($json);
    }

    public function createFPXintent() {
        require_once($this->meta['stripe_path']);
        if($this->getConfig('transaction_mode')) {
            \Stripe\Stripe::setApiKey($this->getConfig('live_private'));
        } else {
            \Stripe\Stripe::setApiKey($this->getConfig('test_private'));
        }

        // order info
        $this->load->model('checkout/order');
        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $amtfpx = $this->currency->format(floatval($order_info['total']), 'MYR', '', false) * 100;

        $payment_intent = \Stripe\PaymentIntent::create([
            'payment_method_types' => ['fpx'],
            'amount' => $amtfpx,
            'currency' => 'myr',
        ]);
        return json_encode($payment_intent);
    }

    public function multibancoPendingOrder() {
        // Create pending order
        $this->load->model('checkout/order');
        $this->model_checkout_order->addOrderHistory($this->session->data['order_id'], $this->getConfig('multibanco_order_status_pending'), 'Multibanco pending', false, false);
    }

    public function multibancoWebhook() {
        $payload = @file_get_contents('php://input');
        $event = json_decode($payload);
        require_once($this->meta['stripe_path']);

        if($event->type == 'source.chargeable' && $event->data->object->type == 'multibanco') {
            if($event->livemode == 'true') {
                \Stripe\Stripe::setApiKey($this->getConfig('live_private'));
            } else {
                \Stripe\Stripe::setApiKey($this->getConfig('test_private'));
            }
            $source = \Stripe\Source::retrieve($event->data->object->id);

            $orderId = $event->data->object->metadata->order_id;
            $charge = \Stripe\Charge::create([
                'amount' => $source['amount'],
                'currency' => $source['currency'],
                'source' => $source['id'],
                'description' => 'Multibanco. Order id: ' . $orderId,
                'metadata' => ['order_id' => $orderId]
            ]);
            // Upd order status
            $this->load->model('checkout/order');
            $this->model_checkout_order->addOrderHistory($orderId, $this->getConfig('order_status_id'), 'Multibanco payment received', false, false);
        }
    }

}