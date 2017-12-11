### POST /vendor/register ###

**Method** `POST /api/v1/vendor/register`

Registers a new vendor.

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
* `country_id` should be a *country_id* as returned in the [GET /common/country](http://oc2-demo.opencartapi.com/docs/v1/API_Resources/Common/GET_country.html) request.
* `zone_id` should be a *zone_id* as returned in the [GET /common/country/{id}](http://oc2-demo.opencartapi.com/docs/v1/API_Resources/Common/GET_country[s]%7Bid%7D.html) request.
* `agree` should be set to 1 if the user accepts the terms and conditions, otherwise registration will fail.
