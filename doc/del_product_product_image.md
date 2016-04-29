### DEL product/{id}/image?files={files} ###

**Method** `DEL /api/v1/product/product/{id}/image?files={files}`

Delete particular images, identified by filename, from the specified product, identified by id.

The image can be a main image or one of the auxiliary images.

Vendor must be logged in and own the product.

**Request parameters**

* `files` - comma separated list of filenames of the images to be deleted. The filenames may be the -500x500.jpg version returned in product requests.
