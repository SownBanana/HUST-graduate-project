<?php
namespace App\Repositories\Asset;

use App\Repositories\BaseRepository;

class AssetRepository extends BaseRepository implements AssetRepositoryInterface
{

    //lấy model tương ứng
    public function getModel()
    {
        return \App\Models\Asset::class;
    }
}
