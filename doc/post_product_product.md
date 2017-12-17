### POST /product/product ###

**Method** `POST /api/v1/product/product`

Adds a new product.

Vendor must be logged in.

The new product will be owned by the logged in vendor.

**Request parameters**

* `category_ids`: a comma-separated list of category ids
* `length_class_id`: integer identifying length unit. Should match a length_class_id returned by [GET /common/length](get_common_length.md).
* `weight_class_id`: integer identifying weight unit. Should match a weight_class_id returned by [GET /common/weight](get_common_weight.md).
* `minimum`: minimum ordering quantity
* `status` : eventually this should be disabled for vendors, but during development, set it to 1 (enabled) to override admin approval step before item is shown on site. By default it's set to 5 (pending approval).
* `expiration_date`: date-time giving expiry date. In `yyyy-MM-dd hh:mm:ss` format
* `unit_class_id`: integer identifying unit class. Should match a unit_class_id returned by [GET /common/units](get_common_units.md).
* `custom_unit`: free text for a custom unit name for this product. If set, this will override `unit_class_id`.
* ...

**Success response**

The id of the added product will be returned.
