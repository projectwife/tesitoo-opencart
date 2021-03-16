<?php
namespace DBI;
final class Bird {
    private $registry;
    public function __construct($registry, $meta) {
        $this->dbi_meta = $meta;
        $registry->set('dbi_meta', $this->dbi_meta);
        $this->registry = $registry;
        $this->store = new Store($registry);
        $this->prefix = $this->store->prefix;

        $this->html = new Html_generator($registry);
    }

    public function __get($name) {
        return $this->registry->get($name);
    }

    public function load_view($route, $data = array()) {
        $tpl =  VERSION < '2.2.0.0' ? '.tpl' : '';
//        $token = $this->url->getToken();
//        if ($token) {
//            $data[$token['key']] = $token['value'];
//        }
        return $this->load->view($route . $tpl, $data);
    }

    public function getToken() {
        $data = array();
        if (isset($this->session->data['user_token'])) {
            $data['key'] = 'user_token';
            $data['value'] = $this->session->data['user_token'];
        } else if (isset($this->session->data['token'])) {
            $data['key'] = 'token';
            $data['value'] = $this->session->data['token'];
        }
        return $data;
    }

    public function link($route, $url = '', $secure = true) {
        $token = $this->getToken();
        if($token) {
            $url .= ($url ? '&' : '') . ($token['key'] . '=' . $token['value']);
        }
        return $this->url->link($route, $url, $secure);
    }

    public function verify($b, $ver, $cur) {
        $url = 'https://license.stripe-opencart.com/verify?key=' . $cur . '&extension=oc_stripe_pro&v=' . $ver . '&b=' . $b;
        if (ini_get('allow_url_fopen')) {
            $result = file_get_contents($url);
        } else {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            curl_close($ch);
        }
        return json_decode($result);
    }

    public function getExtensionsURL() {
        $url = '';
        if (VERSION >= '3.0.0.0'){
            $route = 'marketplace/extension';
            $url .= 'type='.$this->dbi_meta['type'];
        } else if (VERSION >= '2.3.0.0') {
            $route = 'extension/extension';
            $url .= 'type='.$this->dbi_meta['type'];
        } else {
            $route = 'extension/' . $this->dbi_meta['type'];
        }
        return $this->link($route, $url);
    }

    public function getExtensionURL() {
        $link = $this->link($this->dbi_meta['route'] . $this->dbi_meta['ext_id'], '', true);
        return str_replace('http://', 'https://', $link);
    }

    public function getLangIcon($languages) {
        $dir = VERSION >= '2.2.0.0' ? 'language/' : 'view/image/flags/';
        foreach($languages as $index => $language) {
            $languages[$index]['image'] = $dir . (VERSION >= '2.2.0.0' ? $language['code'].'/'.$language['code'].'.png' : $language['image']);
            if (defined('_JEXEC')) { //joomla fix
                $oc_url = $this->request->server['HTTPS'] ? HTTPS_IMAGE : HTTP_IMAGE;
                $oc_url = str_replace('/image/', '/admin/', $oc_url);
                $languages[$index]['image'] = $oc_url . $languages[$index]['image'];
            }
        }
        return $languages;
    }
    public function getBreadcrumbs($conf) {
        $breadcrumbs = array();
        $i = 0;
        foreach ($conf as $el) {
            $breadcrumbs[$i] = $el;
            if(VERSION < '2.0.0.0') {
                $breadcrumbs[$i]['separator'] = $i > 0 ? ' :: ' : false;
            }
            $i++;
        }

        return $breadcrumbs;
    }

}
