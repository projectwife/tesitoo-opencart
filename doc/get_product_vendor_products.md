### GET /product/vendor/{id}/products ###

**Method** `GET /api/v1/product/vendor/{id}/products`

Request products for a vendor, identified by vendor id.

If requesting products belonging to the currently logged in vendor, you are recommended to use [GET /vendor/products](get_vendor_products.md) instead, which will also include products still pending approval as well as those with an expiration date in the past.

**Request parameters**

* `include_expired` (optional) - if set to non-0, products with an expiration date in the past will also be included
