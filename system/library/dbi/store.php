<?php
namespace DBI;
final class Store {
    public $storePrefix;
    public function __construct($registry) {
        $this->config = $registry->get('config');
        $this->request = $registry->get('request');
        $this->dbi_meta = $registry->get('dbi_meta');
        $this->prefix = VERSION >= '3.0.0.0' ? $this->dbi_meta['type'].'_' : '';
        $this->storePrefix = $this->prefix . $this->dbi_meta['ext_id'] . '_';
        // store prefix oc3: payment_extid_ ; store prefix -oc3: extid_
    }

    public function saveStore($store) {
        $data = array();
        foreach ($store as $key => $default) {
//            $key = $this->prefix . $key;
            if (isset($this->request->post[$key])) {
                $data[$key] = $this->request->post[$key];
            } else {
                $data[$key] = '';
            }
        }
        return array(
            'store_key' => $this->prefix . $this->dbi_meta['ext_id'],
            'store' => $data
        );
    }

    private function bprint($value) {
        print('<pre>');
        print_r($value);
        print('</pre>');
    }

    public function getStore($store, $languages = array()) {
        $data = array();
        foreach ($store as $key => $default) {
            $key = $this->prefix . $key;
            if (isset($this->request->post[$key])) {
                $data[$key] = $this->request->post[$key];
            } else {
                $data[$key] = $this->config->get($key);
            }
            if (strlen($data[$key]) == 0 && $default) {
                if (is_array($default)) {
                    foreach ($default as $_key => $_value) {
                        if ($_key === '__LANG__') {
                            foreach ($languages as $language) {
                                if (!isset($data[$key][$language['language_id']]) || !$data[$key][$language['language_id']]) {
                                    $data[$key][$language['language_id']] = $_value;
                                }
                            }
                        } else {
                            $data[$key][$_key] = $_value;
                        }
                    }
                } else {
                    $data[$key] = $default;
                }
            }
        }
        return $data;
    }

}