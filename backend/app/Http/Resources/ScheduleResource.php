<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->name,
            'startDate' => $this->start_time,
            'endDate' => $this->end_time,
            'section' => $this->section->id,
            'course' => $this->section->course->id
        ];
    }
}
