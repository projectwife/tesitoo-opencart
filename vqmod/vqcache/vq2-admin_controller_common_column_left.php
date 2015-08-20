<?php
class ControllerCommonColumnLeft extends Controller {
	public function index() {
		if (isset($this->request->get['token']) && isset($this->session->data['token']) && ($this->request->get['token'] == $this->session->data['token'])) {
			$data['profile'] = $this->load->controller('common/profile');
			$data['menu'] = $this->load->controller('common/menu');
						
			if (!$this->user->getVP()) {
				$data['stats'] = $this->load->controller('common/stats');
			} else {
				$data['stats'] = $this->load->controller('common/vdi_stats');
			}
			

			return $this->load->view('common/column_left.tpl', $data);
		}
	}
}