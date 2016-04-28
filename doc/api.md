# Tesitoo OpenCart API #

The basic REST API is documented here:
http://oc2-demo.opencartapi.com/docs/v1/Getting_Started.html

This document describes the Tesitoo extended API requests.


## admin ##

### POST login ###

**Method** `POST /api/v1/admin/login`

Admin/vendor login

**Request parameters**

* `username` - vendor username

* `password` - plaintext password

---

### POST logout ###

**Method** `POST /api/v1/admin/logout`

Admin/vendor logout

**Request parameters**

*none*

---


## common ##


### GET weight ###

**Method** `GET /api/v1/common/weight`

Returns a list of all the available weight units.

**Request parameters**

*none*

---

### GET length ###

**Method** `GET /api/v1/common/length`

Returns a list of all the available length units.

**Request parameters**

*none*

---


## product##


### GET vendor ###

**Method** `GET /api/v1/product/vendor`

Request a collection of all vendors

**Request parameters**

*none*

---


### GET vendor/{id} ###

**Method** `GET /api/v1/product/vendor/{id}`

Request a specific vendor, identified by vendor id.

**Request parameters**

*none*


---

### GET products ###

**Method** `GET /api/v1/product/vendor/{id}/products`

Request products for a vendor, identified by vendor id.

**Request parameters**

*none*

---

### POST product ###

**Method** `POST /api/v1/product/product`

Adds a new product.

Vendor must be logged in.

The new product will be owned by the logged in vendor.

**Request parameters**

* `category_ids`: a comma-separated list of category ids
* `length_class_id`: integer identifying length unit
(see table oc_length_class_description)
* `weight_class_id`: integer identifying weight unit
(see table oc_weight_class_description)
* `minimum`: minimum ordering quantity
* `status` : eventually this should be disabled for vendors, but during development, set it to 1 (enabled) to override admin approval step before item is shown on site. By default it's set to 5 (pending approval).

**Success response**

The id of the added product will be returned.

---

### POST product/{id} ###

**Method** `POST /api/v1/product/product/{id}`

edit product details

the product must be owned by the current logged in vendor


**Request parameters**

fields for which editing is implemented:

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
* `weight_class_id`: should be one of the values in oc_weight_class table
* `weight`: float
* `length_class_id`: should be one of the values in oc_length_class table
* `length`: float
* `width`: float
* `height`: float


---

### DEL product/{id} ###

**Method** `DEL /api/v1/product/product/{id}`

delete a product (or multiple products), specified by id

The vendor must be logged in.

Currently no check is done on whether this vendor has permission to delete the product.

id may be a single product id or a comma-separated list of product ids

Currently no error is returned if the product id does not exist. Success is alwars returned, unless the vendor is not logged in.


**Request parameters**

*none*

---

### POST product/{id}/image ###

**Method** `POST /api/v1/product/product/{id}/image`

upload an image for the specified product

vendor must be logged in and own the product

**Request parameters**

* `file` - the image file to upload
* `main_product_image`: true for single main image, false for one of auxiliary images
* `sort_order` gives the sort order in which to show auxiliary images. Not used for the main product image. Duplicate values are accepted (images will be shown in arbitrary order).


---

### DEL product/{id}/image?files={files} ###

**Method** `DEL /api/v1/product/product/{id}/image?files={files}`

delete particular images, identified by filename, from the specified product, identified by id

the image can be a main image or one of the auxiliary images

vendor must be logged in and own the product

**Request parameters**

* `files` - comma separated list of filenames of the images to be deleted. The filenames may be the -500x500.jpg version returned in product requests.



---


## vendor ##


### GET order ###

**Method** `GET /api/v1/vendor/order`

Get a list of all the open orders belonging to the current logged in vendor.

**Request parameters**

*none*

---

### GET order/{id} ###

**Method** `GET /api/v1/vendor/order/{id}`

Get details of a specific order, specified by id.

all products will be listed

Only the ordered products in the catalog of the logged-in vendor will be returned.

**Request parameters**

*none*

---

### GET order_product/{id} ###

**Method** `GET /api/v1/vendor/order_product/{id}`

Get details of a specific product in an order, specified by order_product_id.

Note that the id in the URL is the order_product_id, not the order_id.

The product details will only be returned if the ordered product is in the catalog of the logged-in vendor.

**Request parameters**

*none*

---

### POST order_product/{id} ###

**Method** `POST /api/v1/vendor/order_product/{id}`

Edit details of a specific product in an order, specified by order_product_id.

The id in the URL is the order_product_id, not the order_id.

This is most commonly used to change the state of an ordered product.

**Request parameters**

Currently the only supported fields are:

* `order_status_id` (which should be one of the statuses listedin oc_order_status)
  The order entity will also be updated if it contains no other products.

---

### POST register ###

**Method** `POST /api/v1/vendor/register`

Register as a new vendor
Password confirmation check should be done on the client side. Only one field (password) needs to be set.

**Request parameters**

* `company` is required - it's used as the vendor display name

* `country_id` should be an id of a row in the oc_country table

* `zone_id` should be an id of a row in the oc_zone table

* `agree` should be set to 1 if the user accepts the terms and conditions, otherwise registration will fail.

