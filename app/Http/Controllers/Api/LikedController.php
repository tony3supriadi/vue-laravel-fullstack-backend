<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\PostLike;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikedController extends Controller
{
    public function post(Request $request, $id)
    {
        $data = PostLike::where('post_id', $id)
            ->where('user_id', Auth::user()->id)
            ->count();

        if ($data) {
            return $this->sendError('Already like this post.');
        }

        if (Post::find($id)) {
            $liked = PostLike::create([
                'user_id' => Auth::user()->id,
                'post_id' => $id,
                'liked' => (bool) $request->liked
            ]);

            return $this->sendResponse($liked, 'Like successfully.');
        } else {
            return $this->sendError('Post ot found');
        }
    }

    public function comment(Request $request, $id)
    {
        $data = PostLike::where('comment_id', $id)
            ->where('user_id', Auth::user()->id)
            ->count();

        if ($data) {
            return $this->sendError('Already like this comment.');
        }

        if (Post::find($id)) {
            $liked = PostLike::create([
                'user_id' => Auth::user()->id,
                'comment_id' => $id,
                'liked' => (bool) $request->liked
            ]);

            return $this->sendResponse($liked, 'Like successfully.');
        } else {
            return $this->sendError('Comment ot found');
        }
    }
}
