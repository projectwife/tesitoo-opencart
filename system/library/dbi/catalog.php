<?php
namespace DBI;
final class Catalog {
    private $registry;
    public function __construct($registry, $meta) {
        $this->registry = $registry;
        $this->config = $registry->get('config');
        $this->registry->set('dbi_catalog', $this);
        $this->prefix = VERSION >= '3.0.0.0' ? $meta['type'].'_' : '';
        $this->storePrefix = $this->prefix . $meta['ext_id'] . '_';
    }
    private function bprint($value) {
        print('<pre>');
        print_r($value);
        print('</pre>');
    }
    public function __get($name) {
        return $this->registry->get($name);
    }

    public function getConfig($key, $extId, $extType = '') {
        $prefix = VERSION >= '3.0.0.0' ? $extType .'_' : '';
        $key = $prefix . $extId . '_' . $key;
        return $this->config->get($key);
    }

    public function load_view($route, $data = array()) {
        $tpl =  VERSION < '2.2.0.0' ? '.tpl' : '';
        $ver = '2.0';
        $startsWith = substr_compare(VERSION, $ver, 0, strlen($ver)) === 0;
        if ($startsWith) {
            $route = 'default/template/' . $route;
        }
        return $this->load->view($route . $tpl, $data);
    }

}
