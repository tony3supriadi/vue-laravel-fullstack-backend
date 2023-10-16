<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CommentController extends Controller
{

    public function index()
    {
        $comments = Comment::with(['user', 'replies', 'like', 'dislike'])
            ->orderBy('created_at', 'DESC')->get();
        return $this->sendResponse($comments, 'Comments retrieved successfully.');
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $comment = Comment::create($request->all());
        return $this->sendResponse($comment, 'Comment created successfully.');
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'content' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $comment = Comment::find($id);
        if (!$comment) {
            return $this->sendError('Comment not found.');
        }

        $comment->content = $request->content;
        $comment->save();
        return $this->sendResponse($comment, 'Comment updated successfully.');
    }

    public function destroy($id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return $this->sendError('Comment not found.');
        }

        $comment->delete();
        return $this->sendResponse(null, 'Comment deleted successfully.');
    }
}
