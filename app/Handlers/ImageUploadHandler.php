<?php

namespace App\Handlers;

use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageUploadHandler
{
    //  只允许以下后缀名的图片文件上传
    protected $allowed_ext = ["png", "jpg", "gif", "jpeg"];

    public function save($file, $folder, $file_prefix, $max_width = false)
    {
        $folder_name = "uploads/images/$folder/" . date("Y/m/d", time());
        $upload_path = public_path() .'/'.$folder_name;
        $extension = strtolower($file->getClientOriginalExtension()) ?: 'png';

        $filename = $file_prefix . '_'.time().'_'.Str::random(10).'.'.$extension;

        if (!in_array($extension, $this->allowed_ext)) {
            return false;
        }

        $file->move($upload_path, $filename);

        if ($max_width && $extension != 'gif') {
            $this->reduceSize($upload_path.'/'.$filename, $max_width);
        }

        return [
            'path' => "$folder_name/$filename"
        ];
    }

    private function reduceSize($file_path, $max_width)
    {
        $manager = new ImageManager(new Driver());
        $image = $manager->read($file_path);

        $image->scaleDown($max_width);

        $image->save();
    }
}
