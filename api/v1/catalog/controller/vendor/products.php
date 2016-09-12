<?php


class ControllerVendorProductsAPI extends ApiController {

	public function index($args = array()) {

		//must be logged in to see list of own products
		if ($this->user->isLogged()) {

			return $this->response->setOutput($this->getOwnProducts());
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_UNAUTHORIZED, ErrorCodes::ERRORCODE_USER_NOT_LOGGED_IN, "not allowed");
		}
	}

	protected function getOwnProducts() {

		$this->load->model('catalog/vdi_product');
		$this->load->model('tool/image');

		$data = array();
		$products = $this->model_catalog_vdi_product->getProducts($data);

        $result = array();
        foreach($products as $product) {
            $thumb = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            $resultProduct = array(
                "product_id" => $product['product_id'],
                "name" => $product['name'],
                "description" => $product['description'],
                "price" => (float)$product['price'],
                "minimum" => (int)$product['minimum'],
                "quantity" => (int)$product['quantity'],
                "status" => (int)$product['status'],
                "thumb_image" => $thumb);
            $result[] = $resultProduct;
        }

        return $result;
    }
}