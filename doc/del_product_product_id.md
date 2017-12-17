### DEL /product/product/{id} ###

**Method** `DEL /api/v1/product/product/{id}`

Delete a product (or multiple products), specified by id

The vendor must be logged in.

Currently no check is done on whether this vendor has permission to delete the product.

`id` may be a single product id or a comma-separated list of product ids

Currently no error is returned if the product id does not exist. Success is alwars returned, unless the vendor is not logged in.


**Request parameters**

*none*
