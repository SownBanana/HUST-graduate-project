<?php
namespace App\Repositories\Section;

use App\Repositories\BaseRepository;

class SectionRepository extends BaseRepository
{
    public function getModel()
    {
        return \App\Models\Section::class;
    }
}
