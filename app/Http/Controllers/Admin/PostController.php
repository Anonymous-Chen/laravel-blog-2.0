<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Jobs\PostFormFields;
use App\Post;
use App\Http\Requests\PostUpdateRequest;
use App\Http\Requests;
use App\Http\Requests\PostCreateRequest;


class PostController extends Controller
{
    //
    public function index()
    {
        return view('admin.post.index')
            ->withPosts(Post::all());
    }

    /**
     * Show the new post form
     */
    public function create()
    {
        $id = null;
        $data = $this->dispatchNow(new PostFormFields($id));

        return view('admin.post.create', $data);
    }

    /**
     * Store a newly created Post
     *
     * @param PostCreateRequest $request
     */
    public function store(PostCreateRequest $request)
    {
        echo "asa";
        //dd( $request->postFillData() );
        $post = Post::create($request->postFillData());
        echo "asa";
        $post->syncTags($request->get('tags', []));

        return redirect("/admin/post")
            ->withSuccess('New Post Successfully Created.');
    }

    /**
     * Show the post edit form
     *
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {

        $data = $this->dispatchNow( new PostFormFields($id) );

        return view('admin.post.edit', $data);
    }

    /**
     * Update the Post
     *
     * @param PostUpdateRequest $request
     * @param int $id
     */
    public function update(PostUpdateRequest $request, $id)
    {
        echo "ssd";
        $post = Post::findOrFail($id);
        $post->fill($request->postFillData());
        $post->save();
        $post->syncTags($request->get('tags', []));

        if ($request->action === 'continue') {
            return redirect()
                ->back()
                ->withSuccess('Post saved.');
        }

        return redirect('/admin/post')
            ->withSuccess('Post saved.');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->tags()->detach();
        $post->delete();

        return redirect('/admin/post')
            ->withSuccess('Post deleted.');
    }



}
