### POST /admin/forgot_password ###

**Method** `POST /api/v1/admin/forgot_password`

Tells the server to reset the user's password.

The server will notify the user by email, providing a URL to start the reset process. The URL will take the regular browser user to a password reset screen; alternatively, the 32-character hex string which it contains can be used to log in via the [`POST admin/login_by_code`](post_admin_login_by_code.md) API.

**Request parameters**

* `username` - user for whom to reset the password
