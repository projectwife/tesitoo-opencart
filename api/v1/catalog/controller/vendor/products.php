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
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$this->load->model('sale/tesitoo_order');

		$data = array();
		$products = $this->model_catalog_vdi_product->getProducts($data);

        $result = array();
        foreach($products as $product) {
            $thumb = $this->model_tool_image->resize($product['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));
            $resultProduct = array(
                "product_id" => $product['product_id'],
                "name" => $product['name'],
                "description" => $product['description'],
                "price" => (string)number_format($product['price'], 2),
                "minimum" => (int)$product['minimum'],
                "quantity" => (int)$product['quantity'],
                "status" => (int)$product['status'],
                "thumb_image" => $thumb,
                "location" => $product['location'],
                "expiration_date" => $product['expiration_date']);

            $resultProduct['categories'] = array();
            $categoriesToProducts = $this->model_catalog_product->getCategories($product['product_id']);
            foreach ($categoriesToProducts as $categoryToProduct) {
                $resultProduct['categories'][] = $categoryToProduct['category_id'];
            }

            $resultProduct['order_counts'] = array();

            $orderStatusCounts = $this->model_sale_tesitoo_order->getOrderProductStatusCountsByProduct($product['product_id']);
            foreach($orderStatusCounts as $orderStatusCount) {
                if ($orderStatusCount['order_status_name']) {
                    $resultProduct['order_counts'][$orderStatusCount['order_status_name']] =
                        (int)$orderStatusCount['count'];
                }
            }

            $result[] = $resultProduct;
        }

        return $result;
    }
}

?>
