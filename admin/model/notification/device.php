<?php
class ModelNotificationDevice extends Model {
    public function registerDevice($vendor_id, $registration_token) {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "vendor_firebase_device` WHERE vendor_id = '" . (int)$vendor_id . "' AND registration_token = '" . $registration_token . "'");

        if (count($query->row) > 0) {
            echo "found dev reg";
            $this->db->query("UPDATE `" . DB_PREFIX . "vendor_firebase_device` SET last_registered =  NOW, date_modified = NOW(), WHERE vendor_id = '" . (int)$vendor_id . "' AND registration_token = '" . $registration_token . "'");
        }
        else {
            echo "didn't find dev reg";
            $this->db->query("INSERT INTO " . DB_PREFIX . "vendor_firebase_device SET
            vendor_id = '" . (int)$vendor_id . "', registration_token = '" . $registration_token
            . "', last_registered = NOW(), date_added = NOW(), date_modified = NOW()");
        }
    }
}
