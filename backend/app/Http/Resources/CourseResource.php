<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
            'instructor_id' => $this->instructor_id,
            'status' => $this->status,
            'title' => $this->title,
            'type' => $this->type,
            'thumbnail_url' => $this->thumbnail_url,
            'introduce' => $this->introduce,
            'price' => $this->price,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'instructor' => $this->instructor,
            'sections' => $this->whenLoaded('sections'),
            'students' => $this->whenLoaded('students'),
            'statistic' => $this->statistic,
            'rate' => $this->rate,
            'comment' => $this->comment,
            'rate_avg' => $this->rate_avg,
            'total' => $this->total,
        ];
    }
}
