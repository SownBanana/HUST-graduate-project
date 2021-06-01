<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Models\Asset;
use App\Repositories\Asset\AssetRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

// use Intervention\Image\Facades\Image;

class FileUploadController extends Controller
{
    protected $assetRepository;

    public function __construct(AssetRepository $assetRepository)
    {
        $this->assetRepository = $assetRepository;
    }

    /**
     * Handle the incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        if ($request->hasFile('upload')) {
            // try {
            $file = $request->file('upload');
            // \dump($file);
            $fileName = 'uploads/' . Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();

            // Store file data only, not generate random name
            // Storage::disk('local')->put('public/'.$fileName, file_get_contents($file), 'public');
            Storage::disk('s3')->put($fileName, file_get_contents($file), 'public');
            $attr = $request->all();
            $attr['owner_id'] = Auth::user()->id;
            $attr['name'] = isset($request['name']) ? $request->name : $fileName;
            $attr['url'] = Storage::disk('s3')->url($fileName);
            $attr['type'] = $file->getClientMimeType();
            $asset = $this->assetRepository->create($attr);
            // return response()->json(['uploaded'=>true,"url"=>Config::get('app.url').'/storage/'.$fileName]);
            return response()->json(['uploaded' => true, "url" => $attr['url'], "asset" => $asset]);
            // } catch (Exception $e) {
            //     return response()->json(['uploaded'=>false,"error"=>["message"=>$e]]);
            // }
        } else {
            return response()->json(['uploaded' => false, "error" => "Not a file"]);
        }
    }
}
