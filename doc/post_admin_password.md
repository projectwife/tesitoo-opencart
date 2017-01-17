### POST password ###

**Method** `POST /api/v1/admin/password`

Changes the password of the current user.

The client should handle confirmation of the new password (i.e. asking the user to enter it again), but the API only needs one `new_password` field to be filled.

Passwords should be sent as plain text (oauth & ssl should be sufficient to ensure security).

**Request parameters**

* `old_password` - current password
* `new_password` - new password
