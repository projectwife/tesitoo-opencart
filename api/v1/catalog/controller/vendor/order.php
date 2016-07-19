<?php


class ControllerVendorOrderAPI extends ApiController {

	public function index($args = array()) {

		//must be logged in to see list of orders - probably still not tight enough security here
		if ($this->user->isLogged()) {

			if (isset($args['id'])) {
				if ($this->request->isGetRequest()) {
					return $this->response->setOutput($this->getVendorOrder($args['id']));
				}
			}
			else {
				return $this->response->setOutput($this->getVendorOrders());
			}
		}
		else {
			throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_UNAUTHORIZED, ErrorCodes::ERRORCODE_USER_NOT_LOGGED_IN, "not allowed");
		}
	}

	//Get open order list of logged in vendor
	protected function getVendorOrders() {

		$this->load->model('sale/vdi_order');

		$data = array();
		$data['filter_order_status'] = "1"; // Status code of orders with 'Pending' status
		$orders = $this->model_sale_vdi_order->getOrders($data);

		return $orders;
	}

	//Get details of a specific order belonging to this vendor
	//Should return customer details, and products to ship
	protected function getVendorOrder($id) {

		$this->load->model('sale/vdi_order');

		$products = $this->model_sale_vdi_order->getOrderProducts($id);
        //if there were no products in the order belonging to this vendor,
        //return immediately, without any data about the customer
		if (0 == sizeof($products)) {
            return;
		}
		else {
            $order = $this->model_sale_vdi_order->getOrder($id);

            $result = array(
                "order_id" => $order['order_id'],
                "customer_id" => $order['customer_id'],
                "firstname" => $order['firstname'],
                "lastname" => $order['lastname'],
                "email" => $order['email'],
                "telephone" => $order['telephone'],
                "payment_method" => $order['payment_method'],
                "shipping_firstname" => $order['shipping_firstname'],
                "shipping_lastname" => $order['shipping_lastname'],
                "shipping_address_1" => $order['shipping_address_1'],
                "shipping_address_2" => $order['shipping_address_2'],
                "shipping_city" => $order['shipping_city'],
                "shipping_postcode" => $order['shipping_postcode'],
                "shipping_zone_id" => $order['shipping_zone_id'],
                "shipping_zone" => $order['shipping_zone'],
                "shipping_zone_code" => $order['shipping_zone_code'],
                "shipping_country_id" => $order['shipping_country_id'],
                "shipping_country" => $order['shipping_country'],
                "shipping_iso_code_2" => $order['shipping_iso_code_2'],
                "shipping_iso_code_3" => $order['shipping_iso_code_3'],
                "payment_firstname" => $order['payment_firstname'],
                "payment_lastname" => $order['payment_lastname'],
                "payment_address_1" => $order['payment_address_1'],
                "payment_address_2" => $order['payment_address_2'],
                "payment_city" => $order['payment_city'],
                "payment_postcode" => $order['payment_postcode'],
                "payment_zone_id" => $order['payment_zone_id'],
                "payment_zone" => $order['payment_zone'],
                "payment_zone_code" => $order['payment_zone_code'],
                "payment_country_id" => $order['payment_country_id'],
                "payment_country" => $order['payment_country'],
                "payment_iso_code_2" => $order['payment_iso_code_2'],
                "payment_iso_code_3" => $order['payment_iso_code_3']
            );

            $result['products'] = $products;

            return $result;
        }
	}
}
