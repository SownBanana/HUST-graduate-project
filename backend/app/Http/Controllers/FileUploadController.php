<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

// use Intervention\Image\Facades\Image;

class FileUploadController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        if ($request->hasFile('upload')) {
            try {
                $file = $request->file('upload');
                // \dump($file);
                $fileName   =  'uploads/'.Str::uuid()->toString(). '.' . $file->getClientOriginalExtension();
                
                // Store file data only, not generate random name
                Storage::disk('local')->put('public/'.$fileName, file_get_contents($file), 'public');
                
                return response()->json(['uploaded'=>true,"url"=>Config::get('app.url').'/storage/'.$fileName]);
            } catch (Exception $e) {
                return response()->json(['uploaded'=>false,"error"=>["message"=>$e]]);
            }
        } else {
            return response()->json(['uploaded'=>false,"error"=>"Not a file"]);
        }
    }
}
