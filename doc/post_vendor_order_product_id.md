### POST order_product/{id} ###

**Method** `POST /api/v1/vendor/order_product/{id}`

Edit details of a specific product in an order, specified by order_product_id.

The id in the URL is the order_product_id, not the order_id.

This is most commonly used to change the state of an ordered product.

**Request parameters**

Currently the only supported fields are:

* `order_status_id` - one of the statuses listed in DB table `oc_order_status`.
  The order entity will also be updated if it contains no other products.
