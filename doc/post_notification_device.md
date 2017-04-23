### POST device ###

**Method** `POST /api/v1/notification/device`

Associate a device with the current user for the purposes of Firebase Cloud Messaging.

**Request parameters**

* `firebase_device_registration_token` {string} - device registration token (see https://firebase.google.com/docs/cloud-messaging/android/client)
