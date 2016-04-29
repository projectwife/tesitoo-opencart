### POST product ###

**Method** `POST /api/v1/product/product`

Adds a new product.

Vendor must be logged in.

The new product will be owned by the logged in vendor.

**Request parameters**

* `category_ids`: a comma-separated list of category ids
* `length_class_id`: integer identifying length unit. Should match a length_class_id returned by [GET length](common_length.md).
* `weight_class_id`: integer identifying weight unit. Should match a weight_class_id returned by [GET weight](common_weight.md).
* `minimum`: minimum ordering quantity
* `status` : eventually this should be disabled for vendors, but during development, set it to 1 (enabled) to override admin approval step before item is shown on site. By default it's set to 5 (pending approval).

**Success response**

The id of the added product will be returned.
