<?php

namespace App\Services;

use Cloudinary\Uploader as CloudinaryUpload;

class ImageService
{

    /**
     * ImageService upload image method.
     *
     * @param  object $image
     * @return string
     */
    public static function upload($image)
    {
        return CloudinaryUpload::upload($image, [
            'folder' => 'refactory'
        ])['secure_url'];
    }
}
