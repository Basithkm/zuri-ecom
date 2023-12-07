<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\V2\BlogCollection;
use App\Models\Blog;

class BlogController extends Controller
{

    public function index()
    {
      $blogPost = Blog::query();
      return new BlogCollection($blogPost->paginate(10));
    }
}
