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
use Intervention\Image\Facades\Image;

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
        if (is_null($uploadedFile)) return null;
        $tempFile = $uploadedFile->getPath(). DIRECTORY_SEPARATOR . $uploadedFile->getFilename();
//        dd(is_file($tempFile));
        logger($nome);
        logger(str_slug($nome));
        $clientOriginalName = 'imagem-de-' . $nome . '.' . $uploadedFile->getClientOriginalExtension();
        // checking file is valid.
        if ($uploadedFile->isValid()) {
            $imageDir = config('delivery.imageLocation') . DIRECTORY_SEPARATOR;
            if (!Storage::exists($imageDir)) Storage::makeDirectory($imageDir);
            $imageResized = $this->resizeCentrilized($tempFile, 150, $uploadedFile->getClientOriginalExtension());
            Storage::put($imageDir . $clientOriginalName, $imageResized);

            $originalImageDir = config('delivery.originalImageLocation') . DIRECTORY_SEPARATOR;
            if (!Storage::exists($originalImageDir)) Storage::makeDirectory($originalImageDir);
            Storage::put($originalImageDir . $clientOriginalName, file_get_contents($tempFile));

            $thumbnailImageDir = config('delivery.thumbnailImageLocation') . DIRECTORY_SEPARATOR;
            if (!Storage::exists($thumbnailImageDir)) Storage::makeDirectory($thumbnailImageDir);
            $imageResized = $this->resizeCentrilized($tempFile, 80, $uploadedFile->getClientOriginalExtension());
            Storage::put($thumbnailImageDir . $clientOriginalName, $imageResized);
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

    public function saveAttachment(Request $request, $campo, $key)
    {
        $uploadedFile = $request->file($campo);
        if (is_null($uploadedFile)) return null;
        $tempFile = $uploadedFile->getPath(). DIRECTORY_SEPARATOR.$uploadedFile->getFilename();
        $clientOriginalName = $key . '-' . str_slug(substr($uploadedFile->getClientOriginalName(),0,-4)) . '.' . $uploadedFile->getClientOriginalExtension();
        // checking file is valid.
        if ($uploadedFile->isValid()) {
            $fileDir = config('delivery.attachmentLocation') . DIRECTORY_SEPARATOR;
            if (!Storage::exists($fileDir)) Storage::makeDirectory($fileDir);
//            $this->load($tempFile);
//            $this->resizeToHeight(150);
//            $this->save($tempFile,IMAGETYPE_PNG);
            Storage::put($fileDir . $clientOriginalName, file_get_contents($tempFile));
        } else {
            dd($clientOriginalName);
//                // sending back with error message.
//                Session::flash('error', 'uploaded file is not valid');
//                return redirect(route('products.index', $host));
        }
        return $clientOriginalName;
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

    /**
     * @param $file
     * @param $size
     * @param string $format
     * @return string
     */
    private function resizeCentrilized($file, $size, $format='png')
    {
        $baseImg = Image::canvas($size, $size);
        $image = Image::make($file)
            ->resize($size, $size, function ($c) {
                $c->aspectRatio();
                $c->upsize();
            });
        $baseImg
            ->insert($image, 'center')
            ->stream($format);
        return $baseImg->__toString();
    }
}