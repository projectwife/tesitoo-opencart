### POST product/{id} ###

**Method** `POST /api/v1/product/product/{id}`

Edit product details

The product must be owned by the current logged in vendor


**Request parameters**

Fields for which editing is implemented:

* `name`
* `description`
* `tag`
* `meta_title`
* `meta_description`
* `meta_keyword`
* `category_ids`: a comma-separated list of category ids. This will replace the entire previous list of category ids.
* `price`: float
* `quantity`: int
* `minimum`: int
* `model`: text
* `stock_status_id`: should be one of the values in oc_stock_status table
* `weight_class_id`: Should match a weight_class_id returned by [GET weight](common_weight.md).
* `weight`: float
* `length_class_id`: Should match a length_class_id returned by [GET length](common_length.md).
* `length`: float
* `width`: float
* `height`: float
* `expiration_date`: date-time giving expiry date. In `yyyy-MM-dd hh:mm:ss` format
