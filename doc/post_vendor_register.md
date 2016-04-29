### POST register ###

**Method** `POST /api/v1/vendor/register`

Register as a new vendor
Password confirmation check should be done on the client side.

**Request parameters**

* `username`

* `password`

* `firstname`

* `lastname`

* `email`

* `telephone`

* `company` is required - it's used as the vendor display name

* `address_1`
* `address_2`
* `city`
* `postcode`

* `country_id` should be an id of a row in the oc_country table

* `zone_id` should be an id of a row in the oc_zone table

* `agree` should be set to 1 if the user accepts the terms and conditions, otherwise registration will fail.
