# Tesitoo OpenCart API #

The core REST API is documented at:
http://oc2-demo.opencartapi.com/docs/v1/Getting_Started.html (see the section 'API Resources')

The Tesitoo extended API requests are documented here.

Most of them require authentication first. See [the Tesitoo OpenCart API instructions](https://docs.google.com/document/d/19rFh9ekIklVX75kOpjOVkEvCt5RWucAbq9PFDtUu1bA).


#### admin ####

* [POST login](post_admin_login.md)
* [POST logout](post_admin_logout.md)
* [POST password](post_admin_password.md)

#### common ####

* [GET weight](get_common_weight.md)
* [GET length](get_common_length.md)
* [GET order_status](get_order_status.md)
* [GET country](get_common_country.md)
* [GET country/{id}](get_common_country_id.md)

#### product ####

* [GET vendor](get_product_vendor.md)
* [GET vendor/{id}](get_product_vendor_id.md  )
* [GET products](get_product_vendor_products.md)
* [POST product](post_product_product.md)
* [POST product/{id}](post_product_product_id.md)
* [DEL product/{id}](del_product_product_id.md  )
* [POST product/{id}/image](post_product_product_image.md)
* [DEL product/{id}/image?files={files}](del_product_product_image.md)

### vendor ####

* [GET order](get_vendor_order.md)
* [GET order/{id}](get_vendor_order_id.md)
* [GET order_product/{id}](get_vendor_order_product_id.md)
* [POST order_product/{id}](post_vendor_order_product_id.md)
* [POST register](post_vendor_register.md)
* [GET vendor/profile](get_vendor_profile.md)
* [POST vendor/profile](post_vendor_profile.md)
* [POST vendor/profile_image](post_vendor_profile_image.md)
* [DEL vendor/profile_image](del_vendor_profile_image.md)
