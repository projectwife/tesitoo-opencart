<?php

namespace ESP;
final class Back {
    private $registry;
    public function __construct($registry, $meta) {
        $this->registry = $registry;
        $this->registry->set('dbi_esp_back', $this);
        $this->meta = $meta;
    }
    // utils
    private function bprint($value) {
        print('<pre>');
        print_r($value);
        print('</pre>');
    }
    public function __get($name) {
        return $this->registry->get($name);
    }

    // main
    public function getDefaultStore() {
        return array(
            // t1
            $this->meta['ext_id'] . '_' . 'status'                     => '0',
            $this->meta['ext_id'] . '_' . 'geo_zone_id'                => '0',
            $this->meta['ext_id'] . '_' . 'order_status_id'            => '1',
            $this->meta['ext_id'] . '_' . 'total'                      => '0',
            $this->meta['ext_id'] . '_' . 'sort_order'                 => '0',
            $this->meta['ext_id'] . '_' . 'use_stored_cards'           => '0',
            $this->meta['ext_id'] . '_' . 'default_payment_method'     => 'creditcards',
            $this->meta['ext_id'] . '_' . 'license_key'                => '',

            // t2
            $this->meta['ext_id'] . '_' . 'test_public'                => '',
            $this->meta['ext_id'] . '_' . 'test_private'               => '',
            $this->meta['ext_id'] . '_' . 'live_public'                => '',
            $this->meta['ext_id'] . '_' . 'live_private'               => '',
            $this->meta['ext_id'] . '_' . 'transaction_mode'           => '0',
            $this->meta['ext_id'] . '_' . 'charge_mode'                => '1',
            $this->meta['ext_id'] . '_' . 'transaction_description'    => '{order_id} :: {fullname} :: {email}',
            // t3
            $this->meta['ext_id'] . '_' . 'paymenttype_creditcards'    => '1',
            $this->meta['ext_id'] . '_' . 'paymenttype_button'         => '0',
            $this->meta['ext_id'] . '_' . 'paymenttype_alipay'         => '0',
            $this->meta['ext_id'] . '_' . 'alipay_currency'            => '',
            $this->meta['ext_id'] . '_' . 'paymenttype_wechat'         => '0',
            $this->meta['ext_id'] . '_' . 'wechat_currency'            => '',
            $this->meta['ext_id'] . '_' . 'paymenttype_bancontact'     => '0',
            $this->meta['ext_id'] . '_' . 'paymenttype_giropay'        => '0',
            $this->meta['ext_id'] . '_' . 'paymenttype_ideal'          => '0',
            $this->meta['ext_id'] . '_' . 'paymenttype_eps'            => '0',
            $this->meta['ext_id'] . '_' . 'paymenttype_p24'            => '0',
            $this->meta['ext_id'] . '_' . 'paymenttype_multibanco'     => '0',
            $this->meta['ext_id'] . '_' . 'multibanco_order_status_pending' => '1',
            $this->meta['ext_id'] . '_' . 'paymenttype_klarna'         => '0',
            $this->meta['ext_id'] . '_' . 'paymenttype_fpx'            => '0',
            // t4
            $this->meta['ext_id'] . '_' . 'checkout_type'              => 'payment_form',
            $this->meta['ext_id'] . '_' . 'card_template'              => 'template1',
            $this->meta['ext_id'] . '_' . 'title'                      => '',
            $this->meta['ext_id'] . '_' . 'button_text'                => '',
            $this->meta['ext_id'] . '_' . 'button_css_classes'         => '',
            $this->meta['ext_id'] . '_' . 'button_styles'              => '',
            $this->meta['ext_id'] . '_' . 'custom_css'                 => '',
        );
    }

}