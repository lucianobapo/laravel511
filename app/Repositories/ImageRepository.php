<?php
/**
 * Created by PhpStorm.
 * User: luciano
 * Date: 14/04/15
 * Time: 02:30
 */

namespace App\Repositories;


//use Illuminate\Support\Facades\Mail;
//use \Illuminate\Mail\Mailer as Mail;
//use Illuminate\Validation\Validator;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageRepository {
    var $image;
    var $image_type;

    /**
     * @param Request $request
     * @param $nome
     * @return string
     */
    public function saveImageFile(Request $request, $nome)
    {
        $uploadedFile = $request->file('imagem');
        $tempFile = $uploadedFile->getPath(). DIRECTORY_SEPARATOR.$uploadedFile->getFilename();
//        dd(is_file($tempFile));
        $clientOriginalName = 'imagem-de-' . $nome . '.' . $uploadedFile->getClientOriginalExtension();
        // checking file is valid.
        if ($uploadedFile->isValid()) {
            $imageDir = config('filesystems.imageLocation') . DIRECTORY_SEPARATOR;
            if (!Storage::exists($imageDir)) Storage::makeDirectory($imageDir);
//            dd($imageDir . $clientOriginalName);
            $this->load($tempFile);
            $this->resizeToHeight(150);
            $this->save($tempFile,IMAGETYPE_PNG);
//            dd(file_get_contents($tempFile));
//            Storage::put($imageDir . $clientOriginalName, file_get_contents($uploadedFile));
            Storage::put($imageDir . $clientOriginalName, file_get_contents($tempFile));
//            dd($clientOriginalName);
        } else {
            dd($clientOriginalName);
//                // sending back with error message.
//                Session::flash('error', 'uploaded file is not valid');
//                return redirect(route('products.index', $host));
        }
        return $clientOriginalName;
    }

    public function updateImageFile(Request $request, $newFileName, $oldFileName){
        $file = config('filesystems.imageLocation') . DIRECTORY_SEPARATOR . $oldFileName;
        if (Storage::exists($file)) Storage::delete($file);
        return $this->saveImageFile($request,str_slug($newFileName));
    }



    function load($filename) {

        $image_info = getimagesize($filename);
        $this->image_type = $image_info[2];
        if( $this->image_type == IMAGETYPE_JPEG ) {

            $this->image = imagecreatefromjpeg($filename);
        } elseif( $this->image_type == IMAGETYPE_GIF ) {

            $this->image = imagecreatefromgif($filename);
        } elseif( $this->image_type == IMAGETYPE_PNG ) {

            $this->image = imagecreatefrompng($filename);
        }
    }
    function save($filename, $image_type=IMAGETYPE_JPEG, $compression=75, $permissions=null) {

        if( $image_type == IMAGETYPE_JPEG ) {
            imagejpeg($this->image,$filename,$compression);
        } elseif( $image_type == IMAGETYPE_GIF ) {

            imagegif($this->image,$filename);
        } elseif( $image_type == IMAGETYPE_PNG ) {

            imagepng($this->image,$filename,9);
        }
        if( $permissions != null) {

            chmod($filename,$permissions);
        }
    }
    function output($image_type=IMAGETYPE_JPEG) {

        if( $image_type == IMAGETYPE_JPEG ) {
            imagejpeg($this->image);
        } elseif( $image_type == IMAGETYPE_GIF ) {

            imagegif($this->image);
        } elseif( $image_type == IMAGETYPE_PNG ) {

            imagepng($this->image);
        }
    }
    function getWidth() {

        return imagesx($this->image);
    }
    function getHeight() {

        return imagesy($this->image);
    }
    function resizeToHeight($height) {

        $ratio = $height / $this->getHeight();
        $width = $this->getWidth() * $ratio;
        $this->resize($width,$height);
    }

    function resizeToWidth($width) {
        $ratio = $width / $this->getWidth();
        $height = $this->getheight() * $ratio;
        $this->resize($width,$height);
    }

    function scale($scale) {
        $width = $this->getWidth() * $scale/100;
        $height = $this->getheight() * $scale/100;
        $this->resize($width,$height);
    }

    function resize($width,$height) {
        $new_image = imagecreatetruecolor($width, $height);
        if( $this->image_type == IMAGETYPE_GIF || $this->image_type == IMAGETYPE_PNG ) {
            $current_transparent = imagecolortransparent($this->image);
            if($current_transparent != -1) {
                $transparent_color = imagecolorsforindex($this->image, $current_transparent);
                $current_transparent = imagecolorallocate($new_image, $transparent_color['red'], $transparent_color['green'], $transparent_color['blue']);
                imagefill($new_image, 0, 0, $current_transparent);
                imagecolortransparent($new_image, $current_transparent);
            } elseif ($this->image_type == IMAGETYPE_PNG) {
                imagealphablending($new_image, false);
                $color = imagecolorallocatealpha($new_image, 0, 0, 0, 127);
                imagefill($new_image, 0, 0, $color);
                imagesavealpha($new_image, true);
            }
        }
        imagecopyresampled($new_image, $this->image, 0, 0, 0, 0, $width, $height, $this->getWidth(), $this->getHeight());
        $this->image = $new_image;
    }
}