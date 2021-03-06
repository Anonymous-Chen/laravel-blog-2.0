<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Post;
use Carbon\Carbon;
use App\Jobs\BlogIndexData;
use App\Http\Requests;
use App\Tag;


class BlogController extends Controller
{
    public function ind(Request $request)
    {
        $tag = $request->get('tag');
        $data = $this->dispatchNow(new BlogIndexData($tag));
        $layout = $tag ? Tag::layout($tag) : 'blog.layouts.index';
        return view('blog.layouts.index', $data);
    }

    public function showPost($slug, Request $request)
    {
        $post = Post::with('tags')->whereSlug($slug)->firstOrFail();
        $tag = $request->get('tag');
        if ($tag) {
            $tag = Tag::whereTag($tag)->firstOrFail();
        }
        return view($post->layout, compact('post', 'tag'));
    }
}
