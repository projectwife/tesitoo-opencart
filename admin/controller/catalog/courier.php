<?php
class ControllerCatalogCourier extends Controller {
	private $error = array();

  	public function index() {
		$this->load->language('catalog/courier');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/courier');

		$this->getList();
  	}

  	public function insert() {
    	$this->load->language('catalog/courier');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/courier');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_courier->addCourier($this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$this->response->redirect($this->url->link('catalog/courier', 'token=' . $this->session->data['token'] . $url, 'SSL'));
    	}

    	$this->getForm();
  	}

  	public function update() {
    	$this->load->language('catalog/courier');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/courier');

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validateForm()) {
			$this->model_catalog_courier->editCourier($this->request->get['courier_id'], $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$this->response->redirect($this->url->link('catalog/courier', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->getForm();
  	}

  	public function delete() {
    	$this->load->language('catalog/courier');

    	$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('catalog/courier');

		if (isset($this->request->post['selected']) && $this->validateDelete()) {
			foreach ($this->request->post['selected'] as $courier_id) {
				$this->model_catalog_courier->deletecourier($courier_id);
	  		}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			$this->response->redirect($this->url->link('catalog/courier', 'token=' . $this->session->data['token'] . $url, 'SSL'));
		}

    	$this->getList();
  	}

  	private function getList() {
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'courier_name';
		}
		
		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}

		$url = '';

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/courier', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		$data['insert'] = $this->url->link('catalog/courier/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		$data['delete'] = $this->url->link('catalog/courier/delete', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['couriers'] = array();

		$data_filter = array(
			'sort'            => $sort,
			'order'           => $order,
			'start'           => ($page - 1) * $this->config->get('config_limit_admin'),
			'limit'           => $this->config->get('config_limit_admin')
		);

		$this->load->model('tool/image');
		
		$couriers_total = $this->model_catalog_courier->getTotalCouriers($data_filter);  //count courier per page
		$results = $this->model_catalog_courier->getCouriers($data_filter); //get total courier name

		foreach ($results as $result) {
			if (is_file(DIR_IMAGE . $result['courier_image'])) {
				$image = $this->model_tool_image->resize($result['courier_image'], 40, 40);
			} else {
				$image = '';
			}
			
			$total_products = $this->model_catalog_courier->getTotalCouriersByCourierId($result['courier_id']);
				
			$data['couriers'][] = array(
				'courier_id' 		=> $result['courier_id'],
				'courier_name'    	=> $result['courier_name'],
				'description'    	=> $result['description'],
				'image'      		=> $image,
				'total_products'	=> $total_products,
				'sort_order'    	=> $result['sort_order'],
			   	'selected'   		=> isset($this->request->post['selected']) && in_array($result['courier_id'], $this->request->post['selected']),
				'edit'     			=> $this->url->link('catalog/courier/update', 'token=' . $this->session->data['token'] . '&courier_id=' . $result['courier_id'] . $url, 'SSL')
			);
    	}

		$data['heading_title'] = $this->language->get('heading_title');
		$data['text_list'] = $this->language->get('text_list');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_no_results'] = $this->language->get('text_no_results');
		$data['text_image_manager'] = $this->language->get('text_image_manager');
		$data['text_confirm'] = $this->language->get('text_confirm');

		$data['column_image'] = $this->language->get('column_image');
		$data['column_courier_name'] = $this->language->get('column_courier_name');
		$data['column_description'] = $this->language->get('column_description');
    	$data['column_total_products'] = $this->language->get('column_total_products');
		$data['column_sort_order'] = $this->language->get('column_sort_order');
		$data['column_action'] = $this->language->get('column_action');

		$data['button_insert'] = $this->language->get('button_insert');
		$data['button_edit'] = $this->language->get('button_edit');
		$data['button_delete'] = $this->language->get('button_delete');
		$data['button_filter'] = $this->language->get('button_filter');

 		$data['token'] = $this->session->data['token'];

 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		
		if (isset($this->request->get['filter_courier_name'])) {
			$filter_courier_name = $this->request->get['filter_courier_name'];
		} else {
			$filter_courier_name = NULL;
		}

		if (isset($this->request->get['filter_sort_order'])) {
			$filter_sort_order = $this->request->get['filter_sort_order'];
		} else {
			$filter_sort_order = NULL;
		}

		$url = '';

		if (isset($this->request->get['filter_courier_name'])) {
			$url .= '&filter_courier_name=' . $this->request->get['filter_courier_name'];
		}

		if (isset($this->request->get['filter_sort_order'])) {
			$url .= '&filter_sort_order=' . $this->request->get['filter_sort_order'];
		} 

		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		$data['sort_courier_name'] = $this->url->link('catalog/courier&token=' . $this->session->data['token'] . '&sort=courier_name' . $url, 'SSL');
		$data['sort_sort_order'] = $this->url->link('catalog/courier&token=' . $this->session->data['token'] . '&sort=sort_order' . $url, 'SSL');

		$url = '';

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		$pagination = new Pagination();
		$pagination->total = $couriers_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
		$pagination->url = $this->url->link('catalog/courier', 'token=' . $this->session->data['token'] . $url . '&page={page}', 'SSL');

		$data['pagination'] = $pagination->render();
		$data['results'] = sprintf($this->language->get('text_pagination'), ($couriers_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($couriers_total - $this->config->get('config_limit_admin'))) ? $couriers_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $couriers_total, ceil($couriers_total / $this->config->get('config_limit_admin')));

		$data['filter_courier_name'] = $filter_courier_name;
			
		$data['sort'] = $sort;
		$data['order'] = $order;
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/courier_list.tpl', $data));
  	}

  	private function getForm() {
    	$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_form'] = !isset($this->request->get['courier_id']) ? $this->language->get('text_add') : $this->language->get('text_edit');
    	$data['text_enabled'] = $this->language->get('text_enabled');
    	$data['text_disabled'] = $this->language->get('text_disabled');
    	$data['text_default'] = $this->language->get('text_default');
		$data['text_image_manager'] = $this->language->get('text_image_manager');
		$data['text_select'] = $this->language->get('text_select');
		$data['entry_courier_name'] = $this->language->get('entry_courier_name');
		$data['entry_description'] = $this->language->get('entry_description');
		$data['text_browse'] = $this->language->get('text_browse');
		$data['text_clear'] = $this->language->get('text_clear');
		$data['entry_courier_image'] = $this->language->get('entry_courier_image');
    	$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['text_browse'] = $this->language->get('text_browse');
		$data['text_clear'] = $this->language->get('text_clear');
		
    	$data['button_save'] = $this->language->get('button_save');
    	$data['button_cancel'] = $this->language->get('button_cancel');
		
    	$data['tab_general'] = $this->language->get('tab_general');
    	
 		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

 		if (isset($this->error['courier_name'])) {
			$data['error_courier_name'] = $this->error['courier_name'];
		} else {
			$data['error_courier_name'] = '';
		}
		
		if (isset($this->error['description'])) {
			$data['error_description'] = $this->error['description'];
		} else {
			$data['error_description'] = '';
		}

   		if (isset($this->error['sort_order'])) {
			$data['error_sort_order'] = $this->error['sort_order'];
		} else {
			$data['error_sort_order'] = '';
		}

		$url = '';

		if (isset($this->request->get['filter_courier_name'])) {
			$url .= '&filter_courier_name=' . $this->request->get['filter_courier_name'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], 'SSL')
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('catalog/courier', 'token=' . $this->session->data['token'] . $url, 'SSL')
		);

		if (!isset($this->request->get['courier_id'])) {
			$data['action'] = $this->url->link('catalog/courier/insert', 'token=' . $this->session->data['token'] . $url, 'SSL');
		} else {
			$data['action'] = $this->url->link('catalog/courier/update', 'token=' . $this->session->data['token'] . '&courier_id=' . $this->request->get['courier_id'] . $url, 'SSL');
		}

		$data['cancel'] = $this->url->link('catalog/courier', 'token=' . $this->session->data['token'] . $url, 'SSL');

		$data['token'] = $this->session->data['token'];

		if (isset($this->request->get['courier_id']) && ($this->request->server['REQUEST_METHOD'] != 'POST')) {
      		$couriers_info = $this->model_catalog_courier->getcourier($this->request->get['courier_id']);
    	}
		
		if (isset($this->request->post['courier_name'])) {
      		$data['courier_name'] = $this->request->post['courier_name'];
    	} elseif (isset($couriers_info)) {
			$data['courier_name'] = $couriers_info['courier_name'];
		} else {	
      		$data['courier_name'] = '';
    	}
		
		if (isset($this->request->post['description'])) {
      		$data['description'] = $this->request->post['description'];
    	} elseif (isset($couriers_info)) {
			$data['description'] = $couriers_info['description'];
		} else {	
      		$data['description'] = '';
    	}
		
		if (isset($this->request->post['courier_image'])) {
			$data['courier_image'] = $this->request->post['courier_image'];
		} elseif (isset($couriers_info)) {
			$data['courier_image'] = $couriers_info['courier_image'];
		} else {
			$data['courier_image'] = '';
		}
	
		if (isset($this->request->post['sort_order'])) {
      		$data['sort_order'] = $this->request->post['sort_order'];
    	} elseif (isset($couriers_info)) {
			$data['sort_order'] = $couriers_info['sort_order'];
		} else {	
      		$data['sort_order'] = '';
    	}

		$this->load->model('tool/image');
		
		if (isset($this->request->post['courier_image']) && is_file(DIR_IMAGE . $this->request->post['courier_image'])) {
			$data['thumb'] = $this->model_tool_image->resize($this->request->post['courier_image'], 100, 100);
		} elseif (!empty($couriers_info) && is_file(DIR_IMAGE . $couriers_info['courier_image'])) {
			$data['thumb'] = $this->model_tool_image->resize($couriers_info['courier_image'], 100, 100);
		} else {
			$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		
		$data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('catalog/courier_form.tpl', $data));
  	}

  	private function validateForm() {
    	if (!$this->user->hasPermission('modify', 'catalog/courier')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}

    	if ((strlen(utf8_decode($this->request->post['courier_name'])) < 1) || (strlen(utf8_decode($this->request->post['courier_name'])) > 64)) {
      		$this->error['courier_name'] = $this->language->get('error_courier_name');
    	}
		
		if ((strlen(utf8_decode($this->request->post['description'])) < 1) || (strlen(utf8_decode($this->request->post['description'])) > 255)) {
      		$this->error['description'] = $this->language->get('error_description');
    	}

    	if (!$this->error) {
			return TRUE;
    	} else {
			if (!isset($this->error['warning'])) {
				$this->error['warning'] = $this->language->get('error_required_data');
			}
      		return FALSE;
    	}
  	}

  	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'catalog/courier')) {
      		$this->error['warning'] = $this->language->get('error_permission');
    	}
		
		$this->load->model('catalog/courier');

		foreach ($this->request->post['selected'] as $courier_id) {
  			$couriers_total = $this->model_catalog_courier->getTotalCouriersByCourierId($courier_id);
    		if ($couriers_total) {
	  			$this->error['warning'] = sprintf($this->language->get('error_courier'), $couriers_total);	
			}	
	  	} 
		
		if (!$this->error) {
	  		return TRUE;
		} else {
	  		return FALSE;
		}
  	}

}
?>