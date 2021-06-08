<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Repositories\Asset\AssetRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class GetPresignedController extends Controller
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(Request $request)
    {
        $s3 = Storage::disk('s3');
        $client = $s3->getDriver()->getAdapter()->getClient();
        $expiry = "+30 minutes";
        $fileName = 'uploads/' . Str::uuid()->toString() . '.' . $request->file_extension;
        $command = $client->getCommand('PutObject', [
            'Bucket' => Config::get('filesystems.disks.s3.bucket'),
            'Key' => $fileName,
            'ContentType' => $request->type,
            'ACL' => 'public-read'
        ]);
        $s3Request = $client->createPresignedRequest($command, $expiry);
        $attr = $request->all();
        $attr['owner_id'] = Auth::user()->id;
        $attr['name'] = $request->name;
        $attr['size'] = $request->size;
        $attr['url'] = Storage::disk('s3')->url($fileName);
        $attr['type'] = $request->type;
        $asset = $this->assetRepository->create($attr);
        return \response()->json(['status' => true, 'presigned_url' => (string)$s3Request->getUri(), 'file_name' => $fileName, 'url' => $asset->url, 'asset' => $asset]);
    }
}
