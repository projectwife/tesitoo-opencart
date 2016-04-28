# Tesitoo OpenCart API #

The basic REST API is documented at:
http://oc2-demo.opencartapi.com/docs/v1/Getting_Started.html (see the section 'API Resources')

The Tesitoo extended API requests are documented here.


### admin ###

* [POST login](get_admin_login.md)
* [POST logout](get_admin_logout.md)


### common ###

* [GET weight](get_common_weight.md)
* [GET length](get_common_length.md)

### product###

* [GET vendor](get_product_vendor.md)
* [GET vendor/{id}](get_product_vendor_id.md  )
* [GET products](get_product_vendor_products.md)
* [POST product](post_product_product.md)
* [POST product/{id}](post_product_product_id.md)
* [DEL product/{id}](del_product_product_id.md  )
* [POST product/{id}/image](post_product_product_image.md)
* [DEL product/{id}/image?files={files}](del_product_product_image.md)

### vendor ###

* [GET order](get_vendor_order.md)
* [GET order/{id}](get_vendor_order_id.md)
* [GET order_product/{id}](get_vendor_order_product_id.md)
* [POST order_product/{id}](post_vendor_order_product_id.md)
* [POST register](post_vendor_register.md)

