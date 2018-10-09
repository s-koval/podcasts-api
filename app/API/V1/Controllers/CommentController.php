<?php
namespace App\API\V1\Controllers;


use App\Comment;
use App\Http\Requests\CreateCommentRequest;
use App\Podcast;

class CommentController extends BaseController
{
    /**
     * Storing new comment to database for current podcast
     *
     * @param CreateCommentRequest $request
     * @param $podcast_id
     */
    public function store(CreateCommentRequest $request, $podcast_id)
    {
        if(!$podcast = Podcast::find($podcast_id)) {
            return $this->response->error('Podcast with this id doesn\'t exist!', 404);
        }
        if($this->checkForExistComment($podcast, $request)) {
            return $this->response->error('This comment is already exist!', 422);
        }
        try {
            $podcast->comments()->create($request->toArray());
            return $this->response->array($podcast->fresh()->load('comments')->toArray());
        } catch (\Exception $exception) {
            return $this->response->error($exception->getMessage(), 500);
        }
    }

    /**
     * Soft deletion of the comment with current id
     *
     * @param $comment_id
     */
    public function flagComment($comment_id)
    {
        if(!$comment = Comment::find($comment_id)) {
            return $this->response->error('Comment with this id doesn\'t exist!', 404);
        }
        try {
            $podcast = $comment->podcast;
            $comment->delete();
            return $this->response->array($podcast->fresh()->load('comments')->toArray());
        } catch (\Exception $exception) {
            return $this->response->error($exception->getMessage(), 500);
        }
    }


    /**
     * Checking if same comment has been submitted in the past
     *
     * @param $podcast
     * @param $request
     * @return bool
     */
    private function checkForExistComment($podcast, $request)
    {
        $comment = $podcast->comments()->get()
                    ->filter(function ($comment) use ($request) {
                        return $comment->author_name === $request->author_name &&
                            $comment->author_email === $request->author_email &&
                            $comment->comment === $request->comment;
                    })->first();
        return !!$comment;
    }
}