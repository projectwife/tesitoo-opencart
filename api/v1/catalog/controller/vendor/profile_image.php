<?php


class ControllerVendorProfileImageAPI extends ApiController {

    public function index($args = array()) {
        if ($this->user->isLogged()) {
            if($this->request->isPostRequest()) {
                $this->post();
            }
            else if ($this->request->isDeleteRequest()) {
                $this->delete();
            }
            else {
                throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_NOT_FOUND, ErrorCodes::ERRORCODE_METHOD_NOT_FOUND, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_METHOD_NOT_FOUND));
            }
        }
        else {
            throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_UNAUTHORIZED, ErrorCodes::ERRORCODE_USER_NOT_LOGGED_IN, "not allowed");
        }
    }

    protected function post() {
        $json = array();

        $userName = $this->user->getUserName();
        $tmpFileName = $this->request->files['file']['tmp_name'];
        $srcFileName = urldecode($this->request->files['file']['name']);
        $tmpfilesize = filesize($this->request->files['file']['tmp_name']);
        $vendorId = $this->user->getVP();

        //file type must be acceptable
        $imageType = exif_imagetype($this->request->files['file']['tmp_name']);
        if ((IMAGETYPE_GIF != $imageType)
            && (IMAGETYPE_JPEG != $imageType)
            && (IMAGETYPE_PNG != $imageType)) {
            throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_FILE_ERROR, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_FILE_ERROR));
        }

        //file size must be >0
        if (0 >= $tmpfilesize) {
            throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_FILE_ERROR, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_FILE_ERROR));
        }

        //append timestamp to all uploaded image filenames to ensure uniqueness
        $path_parts = pathinfo($srcFileName);
        $fileNameTimestamped = $path_parts['filename']
            . "_" . time() . "." . $path_parts['extension'];
        $destination = "catalog/" . $userName . "/" . $fileNameTimestamped;

        //move tmpfile to proper vendor-specific location
        if (!rename($tmpFileName, DIR_IMAGE . $destination)) {
            throw new ApiException(ApiResponse::HTTP_RESPONSE_CODE_BAD_REQUEST, ErrorCodes::ERRORCODE_FILE_ERROR, ErrorCodes::getMessage(ErrorCodes::ERRORCODE_FILE_ERROR));
        }

        $this->load->model('catalog/vdi_vendor_profile');
		$this->load->model('tool/image');

        //ask model to associate image in db, providing vendor id and destination filename
        $this->model_catalog_vdi_vendor_profile->setVendorProfileImage($vendorId, $destination);

        $json['filename'] = $fileNameTimestamped;

        $json['thumbnailPath'] = $this->model_tool_image->resize($destination, $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));

        $this->response->setOutput($json);
    }

    protected function delete() {

        $vendorId = $this->user->getVP();

        $this->load->model('catalog/vdi_vendor_profile');

        $currentImage = $this->model_catalog_vdi_vendor_profile->getVendorProfileImage($vendorId);

        //instruct the model to set the profile image field to an empty string
        $this->model_catalog_vdi_vendor_profile->setVendorProfileImage($vendorId, "");

        //remove file from image catalog
        if (file_exists(DIR_IMAGE . $currentImage)) {
            unlink(DIR_IMAGE . $currentImage);
        }
        //don't clean up image cache - assume cleared periodically by routine maintenance

    }
}

?>

