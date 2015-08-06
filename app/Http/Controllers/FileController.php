<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller {

    public function showImage($host, $file){
        $imageDir = config('filesystems.imageLocation') . DIRECTORY_SEPARATOR;
        if (!Storage::exists($imageDir . $file)) return abort(404, trans('app.notFound'));
        $headers = array();
        $headers['content-type'] = Storage::mimeType($imageDir . $file);
        $headers['Cache-Control'] = 'max-age='.(60*60*24*7);
        $headers['content-transfer-encoding'] = 'binary';
        $headers['content-disposition'] = 'inline filename="'.$file.'"';
        $headers['content-length'] = Storage::size($imageDir . $file);
        return response(Storage::get($imageDir . $file), 200, $headers);
	}

    public function showAttachment($host, $file){
        $fileDir = config('delivery.attachmentLocation') . DIRECTORY_SEPARATOR;
        if (!Storage::exists($fileDir . $file)) return abort(404, trans('app.notFound'));
        $headers = array();
        $headers['content-type'] = Storage::mimeType($fileDir . $file);
        $headers['Cache-Control'] = 'max-age='.(60*60*24*7);
        $headers['content-transfer-encoding'] = 'binary';
        $headers['content-disposition'] = 'inline filename="'.$file.'"';
        $headers['content-length'] = Storage::size($fileDir . $file);
        return response(Storage::get($fileDir . $file), 200, $headers);
    }

}
