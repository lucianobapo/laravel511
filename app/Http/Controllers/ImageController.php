<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller {

    public function show($host, $file){
        $imageDir = config('filesystems.imageLocation') . DIRECTORY_SEPARATOR;
//        dd($imageDir . $file);
        if (!Storage::exists($imageDir . $file)) return abort(404, trans('app.notFound'));

        $headers = array();
        $headers['content-type'] = Storage::mimeType($imageDir . $file);
        $headers['Cache-Control'] = 'max-age='.(60*60*24*7);
        $headers['content-transfer-encoding'] = 'binary';
        $headers['content-disposition'] = 'inline filename="'.$file.'"';
        $headers['content-length'] = Storage::size($imageDir . $file);
        return response(Storage::get($imageDir . $file), 200, $headers);
	}

}
