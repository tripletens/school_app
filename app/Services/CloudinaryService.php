<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Cloudinary\Api\Upload\UploadApi;

class CloudinaryService
{
    public $name;
    public $api_key;
    public $secret_key;
    public $url;

    public function __construct($name, $api_key, $secret_key, $url)
    {
        $this->name = $name;
        $this->api_key = $api_key;
        $this->secret_key = $secret_key;
        $this->url = $url;

        Configuration::instance($this->url);
    }

    public function image_upload($folder, $image)
    {
        $upload = new UploadApi();
        $result = $upload->upload($image->getRealPath(), [
            'folder' => 'test',
            'discard_original_filename' => true,
            'transformation' => [
                'fetch_format' => 'auto',
                'quality' => 'auto:eco',
            ],
        ]);

        if ($result && $result !== null) {
            return $result;
        } else {
            return false;
        }
    }
}

?>
