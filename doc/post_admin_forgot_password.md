### POST forgot_password ###

**Method** `POST /api/v1/admin/forgot_password`

Tells the server to reset the user's password.

The server will notify the user by email.

**Request parameters**

* `username` - user for whom to reset the password
