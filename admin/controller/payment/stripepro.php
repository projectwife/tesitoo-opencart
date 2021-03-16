<?php
class ControllerPaymentStripePro extends Controller {
    private $error = array();
    private $currencies = array('GBP', 'HKD', 'USD', 'CHF', 'SGD', 'SEK', 'DKK', 'NOK', 'JPY', 'CAD', 'AUD', 'EUR', 'NZD', 'KRW', 'THB');

    private $bird;
    private $full_route;
    private $meta = array(
        'ext_id'   => 'stripepro',
        'type'     => 'payment',
        'route'    => 'payment/',
        'mode'     => 'demo1',
    );
    private $store;

    public function __construct($registry) {
        parent::__construct($registry);
        $this->bird = new DBI\Bird($registry, $this->meta);
        $this->full_route = $this->meta['route'] . $this->meta['ext_id'];
        $this->back = ($espro = $this->registry->get('dbi_esp_back')) ? $espro : new ESP\Back($registry, $this->meta);

        $this->store = $this->back->getDefaultStore();
    }

    private function bprint($value) {
        print('<pre>');
        print_r($value);
        print('</pre>');
    }

    public function index() {
        $this->load->language($this->full_route);
        //~ lang
        $data = array();
        $this->loadLangVars($data);
        //~

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        $i18n_strings = $this->load->language($this->full_route);
        $this->bird->html->setLangs($i18n_strings);

        // Load language codes
        $this->load->model('localisation/language');
        $languages = $this->model_localisation_language->getLanguages();
        $languages = $this->bird->getLangIcon($languages);
        // Restore the store
        $this->store = $this->bird->store->getStore($this->store, $languages);


        // Handle save
        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $save = $this->bird->store->saveStore($this->store);
            $this->model_setting_setting->editSetting($save['store_key'], $save['store']);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->response->redirect($this->bird->getExtensionURL());
        }


        $data['breadcrumbs'] = $this->bird->getBreadcrumbs([
            [
                'text'      => $this->language->get('text_home'),
                'href'      => $this->bird->link('common/dashboard', '', true)
            ],
            [
                'text'      => $this->language->get('text_extension'),
                'href'      => $this->bird->getExtensionsURL(),
            ],
            [
                'text'      => $this->language->get('heading_title'),
                'href'      => $this->bird->getExtensionURL()
            ]
        ]);

        $data['action'] = $this->bird->getExtensionURL();
        $data['cancel'] = $this->bird->getExtensionsURL();


        // Warning
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        // Errors todo refactor
        if (isset($this->error['test_public'])) {
            $data['error_test_public'] = $this->error['test_public'];
        } else {
            $data['error_test_public'] = '';
        }
        if (isset($this->error['test_private'])) {
            $data['error_test_private'] = $this->error['test_private'];
        } else {
            $data['error_test_private'] = '';
        }
        if (isset($this->error['live_public'])) {
            $data['error_live_public'] = $this->error['live_public'];
        } else {
            $data['error_live_public'] = '';
        }
        if (isset($this->error['live_private'])) {
            $data['error_live_private'] = $this->error['live_private'];
        } else {
            $data['error_live_private'] = '';
        }
        if (isset($this->error['license'])) {
            $data['error_license'] = $this->error['license'];
        } else {
            $data['error_license'] = '';
        }

        $data['license_key'] = $this->getStoreVar('license_key');


        $b = $this->request->server['HTTPS'] ? HTTPS_SERVER : HTTP_SERVER;
        $data['b']              = $b;
        $storePrefix = $this->bird->store->storePrefix;
        $data['storePrefix']    = $storePrefix;
        $data['mode']           = $this->meta['mode'];

        $data['header']         = $this->load->controller('common/header');
        $data['column_left']    = $this->load->controller('common/column_left');
        $data['footer']         = $this->load->controller('common/footer');

        $data['html_tab1'] = $this->getTab1();
        $data['html_tab2'] = $this->getTab2();
        $data['html_tab3'] = $this->getTab3();
        $data['html_tab4'] = $this->getTab4();


        $this->response->setOutput($this->bird->load_view($this->full_route, $data));
    }

    private function getTab1() {
        $return = '';
        $size = 4;

        $var = 'status';
        $elements = array(
            '1' => $this->language->get('text_enabled'),
            '0' => $this->language->get('text_disabled')
        );
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getSelectHorizontal($var, $elements, $value, $size);

        $var = 'geo_zone_id';
        $elements = array(
            '0' => $this->language->get('text_all_zones')
        );
        $this->load->model('localisation/geo_zone');
        $geozones = $this->model_localisation_geo_zone->getGeoZones();
        foreach ($geozones as $zone) {
            $elements[$zone['geo_zone_id']] = $zone['name'];
        }
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getSelectHorizontal($var, $elements, $value, $size);

        $var = 'order_status_id';
        $elements = [];
        $this->load->model('localisation/order_status');
        $order_statuses = $this->model_localisation_order_status->getOrderStatuses();
        foreach ($order_statuses as $status) {
            $elements[$status['order_status_id']] = $status['name'];
        }
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getSelectHorizontal($var, $elements, $value, $size);

        $var = 'total';
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getInputHorizontal($var, $value, False, $size);

        $var = 'sort_order';
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getInputHorizontal($var, $value, False, $size);

        $var = 'use_stored_cards';
        $elements = array(
            '1' => $this->language->get('text_yes'),
            '0' => $this->language->get('text_no')
        );
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getSelectHorizontal($var, $elements, $value, $size);

        $var = 'default_payment_method';
        $elements = array(
            'storedcard'    => $this->language->get('text_storedcard'),
            'creditcards'   => $this->language->get('entry_paymenttype_creditcards'),
            'alipay'        => $this->language->get('entry_paymenttype_alipay'),
            'wechat'        => $this->language->get('entry_paymenttype_wechat'),
            'bancontact'    => $this->language->get('entry_paymenttype_bancontact'),
            'giropay'       => $this->language->get('entry_paymenttype_giropay'),
            'ideal'         => $this->language->get('entry_paymenttype_ideal'),
            'eps'           => $this->language->get('entry_paymenttype_eps'),
            'p24'           => $this->language->get('entry_paymenttype_p24'),
            'multibanco'    => $this->language->get('entry_paymenttype_multibanco'),
            'klarna'        => $this->language->get('entry_paymenttype_klarna'),
            'fpx'           => $this->language->get('entry_paymenttype_fpx'),
        );
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getSelectHorizontal($var, $elements, $value, $size);

        return $return;
    }

    private function getTab2() {
        $return = '';
        $size = 4;

        $var = 'test_public';
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getInputHorizontal($var, $value, False, $size);

        $var = 'test_private';
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getInputHorizontal($var, $value, False, $size);

        $var = 'live_public';
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getInputHorizontal($var, $value, False, $size);

        $var = 'live_private';
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getInputHorizontal($var, $value, False, $size);

        $var = 'transaction_mode';
        $elements = array(
            '1' => $this->language->get('text_live'),
            '0' => $this->language->get('text_test')
        );
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getSelectHorizontal($var, $elements, $value, $size);

        $var = 'charge_mode';
        $elements = array(
            '1' => $this->language->get('text_charge'),
            '0' => $this->language->get('text_authorize')
        );
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getSelectHorizontal($var, $elements, $value, $size);

        $var = 'transaction_description';
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getInputHorizontal($var, $value, False, $size);


        return $return;
    }

    private function getTab3() {
        $return = '';
        $size = 4;
        // currencies
        $elementsCurrencies = array();
        $this->load->model('localisation/currency');
        $currencies = $this->model_localisation_currency->getCurrencies();
        foreach ($currencies as $currency) {
            if (in_array($currency['code'], $this->currencies)) {
                $elementsCurrencies[$currency['code']] = $currency['title'];
            }
        }
        $elementsEnabledDisabled = array(
            '1' => $this->language->get('text_enabled'),
            '0' => $this->language->get('text_disabled')
        );

        $var = 'paymenttype_creditcards';
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getSelectHorizontal($var, $elementsEnabledDisabled, $value, $size);
        $var = 'paymenttype_button';
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getSelectHorizontal($var, $elementsEnabledDisabled, $value, $size);

        $var = 'paymenttype_alipay';
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getSelectHorizontal($var, $elementsEnabledDisabled, $value, $size);
        $var = 'alipay_currency';
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getSelectHorizontal($var, $elementsCurrencies, $value, $size, False);

        $var = 'paymenttype_wechat';
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getSelectHorizontal($var, $elementsEnabledDisabled, $value, $size);
        $var = 'wechat_currency';
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getSelectHorizontal($var, $elementsCurrencies, $value, $size, False);

        $var = 'paymenttype_bancontact';
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getSelectHorizontal($var, $elementsEnabledDisabled, $value, $size);
        $var = 'paymenttype_giropay';
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getSelectHorizontal($var, $elementsEnabledDisabled, $value, $size);
        $var = 'paymenttype_ideal';
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getSelectHorizontal($var, $elementsEnabledDisabled, $value, $size);
        $var = 'paymenttype_eps';
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getSelectHorizontal($var, $elementsEnabledDisabled, $value, $size);
        $var = 'paymenttype_p24';
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getSelectHorizontal($var, $elementsEnabledDisabled, $value, $size);

        $var = 'paymenttype_multibanco';
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getSelectHorizontal($var, $elementsEnabledDisabled, $value, $size);

        $var = 'multibanco_order_status_pending';
        $elements = [];
        $this->load->model('localisation/order_status');
        $order_statuses = $this->model_localisation_order_status->getOrderStatuses();
        foreach ($order_statuses as $status) {
            $elements[$status['order_status_id']] = $status['name'];
        }
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getSelectHorizontal($var, $elements, $value, $size, False);

        $var = 'paymenttype_klarna';
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getSelectHorizontal($var, $elementsEnabledDisabled, $value, $size);
        $var = 'paymenttype_fpx';
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getSelectHorizontal($var, $elementsEnabledDisabled, $value, $size);

        return $return;
    }

    private function getTab4() {
        $return = '';
        $size = 4;

        $var = 'checkout_type';
        $elements = array(
            'checkout_page' => $this->language->get('text_checkout_page'),
            'payment_form'  => $this->language->get('text_form')
        );
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getSelectHorizontal($var, $elements, $value, $size);

        $var = 'card_template';
        $elements = array(
            'template1' => $this->language->get('text_card_template1'),
            'template2' => $this->language->get('text_card_template2')
        );
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getSelectHorizontal($var, $elements, $value, $size);

        $var = 'title';
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getInputHorizontal($var, $value, False, $size);

        $var = 'button_text';
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getInputHorizontal($var, $value, False, $size);

        $var = 'button_css_classes';
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getInputHorizontal($var, $value, False, $size);

        $var = 'button_styles';
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getInputHorizontal($var, $value, False, $size);

        $var = 'custom_css';
        $value = $this->getStoreVar($var);
        $return .= $this->bird->html->getMultilineHorizontal($var, $value, False, $size);

        return $return;
    }

    private function getStoreVar($name) {
        $storePrefix = $this->bird->store->storePrefix;
        return $this->store[$storePrefix . $name];
    }
    private function getPostVar($name) {
        $storePrefix = $this->bird->store->storePrefix;
        return $this->request->post[$storePrefix . $name];
    }
    private function getConfigVar($name) {
        $storePrefix = $this->bird->store->storePrefix;
        return $this->config->get($storePrefix . $name);
    }

    protected function validate() {
        $this->load->language($this->full_route);
        if (!$this->user->hasPermission('modify', $this->full_route)) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->getPostVar('license_key')) {
            $this->error['license'] = $this->language->get('error_license');
        } else {
            $cur = $this->getPostVar('license_key');
            $prev = $this->getConfigVar('license_key');

            if ($cur == $prev) {
                $ver = $this->language->get('version');
                $b = $this->request->server['HTTPS'] ? HTTPS_SERVER : HTTP_SERVER;
                $json = $this->bird->verify($b, $ver, $cur);
                if (!$json->{'success'}) {
                    if ($json->{'type'} == 'expired') {
                        $this->error['license'] = $this->language->get('error_license_expired');
                    } else {
                        $this->error['license'] = $this->language->get('error_license');
                    }
                }
            } else {
                if ($this->getPostVar('license_is_activated') == 0) {
                    $this->error['license'] = $this->language->get('error_activate_license');
                }
            }
        }
        return !$this->error;
    }

    private function loadLangVars(&$data) {
        $data['heading_title']        = $this->language->get('heading_title');
        $data['version']              = $this->language->get('version');
        $data['button_save']            = $this->language->get('button_save');
        $data['button_cancel']          = $this->language->get('button_cancel');
    }
}
