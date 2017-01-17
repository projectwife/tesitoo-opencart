### POST vendor/profile_image ###

**Method** `POST /api/v1/vendor/profile_image`

Upload a profile image for the logged in vendor. The existing image (if any) is replaced.

Should be sent as `form_data` (not as `x-www-form-urlencoded`)

**Request parameters**

* `file` - the image file to upload
