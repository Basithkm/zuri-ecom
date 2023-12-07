<?php

namespace App\Http\Resources\V2;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\Blog;

class BlogCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return [
            'data' => $this->collection->map(function($data) {
                return [
                    'id' => $data->id,
                    'name' => $data->title,
                    'status' => $data->status,
                    'banner' => $data->banner,
                    'slug' => $data->slug,
                    'blog_category' => $data->category_id,
                    'short_description' => $data->short_description,
                    'description' => $data->description,
                ];
            })
        ];
    }

    public function with($request)
    {
        return [
            'success' => true,
            'status' => 200
        ];
    }
}

