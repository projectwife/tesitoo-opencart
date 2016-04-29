### POST product/{id}/image ###

**Method** `POST /api/v1/product/product/{id}/image`

Upload an image for the specified product.

Vendor must be logged in and own the product.

**Request parameters**

* `file` - the image file to upload
* `main_product_image`: true for single main image, false for one of auxiliary images
* `sort_order` gives the sort order in which to show auxiliary images. Not used for the main product image. Duplicate values are accepted (images will be shown in arbitrary order).
