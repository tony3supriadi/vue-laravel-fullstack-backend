<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{

    public function index()
    {
        $posts = Post::with(['author', 'comments', 'likes'])
            ->orderBy('created_at', 'DESC');

        if (request()->get('status')) {
            $posts->where('status', request()->get('status'));
        }

        if (request()->get('author')) {
            $posts->where('author', request()->get('author'));
        }

        if (request()->get('start') && request()->get('end')) {
            $posts->whereDate('published_at', '>=', request()->get('start'));
            $posts->whereDate('published_at', '<=', request()->get('end'));
        }

        $results = $posts->paginate(10);
        return $this->sendResponse($results, 'Posts retrieved successfully.');
    }

    public function show($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return $this->sendError('Post not found.');
        }
        return $this->sendResponse($post, 'Post retrieved successfully.');
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $data = $request->all();
        if ($request->status === "published") {
            $data['published_at'] = now();
        } else {
            $data['published_at'] = null;
        }

        $post = Post::create($data);
        return $this->sendResponse($post, 'Post created successfully.');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'content' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $post = Post::find($id);
        if (!$post) {
            return $this->sendError('Post not found.');
        }

        $post->title = $request->title;
        $post->content = $request->content;
        $post->status = $request->status;

        if ($post->status != 'published' && $request->status == 'published') {
            $post->published_at = now();
        }

        $post->save();

        return $this->sendResponse($post, 'Post updated successfully.');
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        if (!$post) {
            return $this->sendError('Post not found.');
        }

        $post->delete();
        return $this->sendResponse(null, 'Post deleted successfully.');
    }
}
