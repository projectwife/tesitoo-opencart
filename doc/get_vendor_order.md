### GET order ###

**Method** `GET /api/v1/vendor/order`

Get a list of all the open orders belonging to the current logged in vendor.

**Request parameters**

- `filter_order_status`: a comma separated list of integers matching one or more of the `order_status_id` values as returned by the `GET /common/order_status` request. If the parameter is omitted, orders of any status will be returned.
