# Tesitoo OpenCart API #

The core REST API is documented at:
http://oc2-demo.opencartapi.com/docs/v1/Getting_Started.html (see the section 'API Resources')

The Tesitoo extended API requests are documented here.

Most of them require authentication first. See [the Tesitoo OpenCart API instructions](https://docs.google.com/document/d/19rFh9ekIklVX75kOpjOVkEvCt5RWucAbq9PFDtUu1bA).


#### admin ####

* [POST admin/login](post_admin_login.md)
* [POST admin/logout](post_admin_logout.md)
* [POST admin/password](post_admin_password.md)
* [POST admin/forgot_password](post_admin_forgot_password.md)

#### common ####

* [GET common/weight](get_common_weight.md)
* [GET common/length](get_common_length.md)
* [GET common/order_status](get_order_status.md)
* [GET common/country](get_common_country.md)
* [GET common/country/{id}](get_common_country_id.md)

#### product ####

* [GET product/vendor](get_product_vendor.md)
* [GET product/vendor/{id}](get_product_vendor_id.md  )
* [GET product/vendor/{id}/products](get_product_vendor_products.md)
* [POST product/product](post_product_product.md)
* [POST product/product/{id}](post_product_product_id.md)
* [DEL product/product/{id}](del_product_product_id.md)
* [POST product/product/{id}/image](post_product_product_image.md)
* [DEL product/product/{id}/image?files={files}](del_product_product_image.md)

### vendor ####

* [GET vendor/order](get_vendor_order.md)
* [GET vendor/order/{id}](get_vendor_order_id.md)
* [GET vendor/order_product/{id}](get_vendor_order_product_id.md)
* [POST vendor/order_product/{id}](post_vendor_order_product_id.md)
* [POST vendor/register](post_vendor_register.md)
* [GET vendor/profile](get_vendor_profile.md)
* [POST vendor/profile](post_vendor_profile.md)
* [POST vendor/profile_image](post_vendor_profile_image.md)
* [DEL vendor/profile_image](del_vendor_profile_image.md)
* [GET vendor/products](get_vendor_products.md)
