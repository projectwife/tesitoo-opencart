### POST profile ###

**Method** `POST /api/v1/vendor/profile`

Sets the vendor profile data for the currently logged-in vendor.

Currently the following properties can be set:

* `company`
* `vendor_description`
* `firstname`
* `lastname`
* `email`
* `telephone`
* `address_1`
* `address_2`
* `city`
* `postcode`
* `country_id` should be a *country_id* as returned in the [GET /common/country](http://oc2-demo.opencartapi.com/docs/v1/API_Resources/Common/GET_country.html) request.
* `zone_id` should be a *zone_id* as returned in the [GET /common/country/{id}](http://oc2-demo.opencartapi.com/docs/v1/API_Resources/Common/GET_country[s]%7Bid%7D.html) request.

Currently `password` cannot be changed. The vendor profile image can also
not be set (separate API requests will be provided).
