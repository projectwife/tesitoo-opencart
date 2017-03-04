### POST login_by_code ###

**Method** `POST /api/v1/admin/login_by_code`

Vendor login by code. This is for the purpose of logging in after requesting a password reset. The code will be a 32-character hex string sent by email to the registered email address. Login using the code is possible only once, and only within a short time after requesting the password reset.

**Request parameters**

* `code` - one-use code consisting of a string of hex characters
