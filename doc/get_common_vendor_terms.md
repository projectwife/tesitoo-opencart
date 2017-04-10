### GET vendor_terms ###

**Method** `GET /api/v1/common/vendor_terms`

Returns the terms and conditions text for vendor sign-up.

The terms and conditions is actually configurable via the Global Vendor Settings "Sign Up Policy" setting and the text it references in 'Catalog->Information'. So it could happen that no appropriate text has been set.

**Request parameters**

*none*

**Result**

`vendor_terms` object containing
* `title` - text field
* `description` - HTML text

If no terms and conditions are configured, the `vendor_terms` object will be empty.
