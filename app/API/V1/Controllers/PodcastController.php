<?php
namespace App\API\V1\Controllers;


use App\API\V1\Transformers\PodcastTransformer;
use App\Http\Requests\CreatePodcastRequest;
use App\Http\Requests\UpdatePodcastRequest;
use App\Podcast;
use Intervention\Image\Facades\Image;

class PodcastController extends BaseController
{
    /**
     * Returning paginated podcasts collection by given status
     *
     * @param $status
     * @return \Dingo\Api\Http\Response
     */
    public function index($status)
    {
        switch ($status) {
            case 'review' :
                $podcasts = Podcast::forReview();
                break;
            case 'published' :
                $podcasts = Podcast::published();
                break;
            default:
                return $this->response->error('Podcasts with this status doesn\'t supported!', 404);
        }


        return $this->response->paginator($podcasts->paginate(12), new PodcastTransformer);
    }

    /**
     * Returning podcast by given id
     *
     * @param $id
     */
    public function show($id) {
        if(!$podcast = Podcast::with('comments')->find($id)) {
            return $this->response->error('Podcast with this id doesn\'t exist!', 404);
        } else {
            return $this->response->array($podcast->toArray());
        }
    }

    /**
     * Storing new podcast to database
     *
     * @param CreatePodcastRequest $request
     */
    public function store(CreatePodcastRequest $request)
    {
        try {
            $podcast = Podcast::create($request->toArray());
            if($request->image !== null) {
                $podcast->image = $this->generateImage($request->image);
                $podcast->save();
            }
            return $this->response->array($podcast->fresh()->load('comments')->toArray());
        } catch (\Exception $exception) {
            return $this->response->error($exception->getMessage(), 500);
        }
    }

    /**
     * Updating existing podcast by given id
     *
     * @param UpdatePodcastRequest $request
     * @param $id
     */
    public function update(UpdatePodcastRequest $request, $id)
    {
        if(!$podcast = Podcast::with('comments')->find($id)) {
            return $this->response->error('Podcast with this id doesn\'t exist!', 404);
        }
        try {
            $podcast->update($request->toArray());
            return $this->response->array($podcast->fresh()->toArray());
        } catch (\Exception $exception) {
            return $this->response->error($exception->getMessage(), 500);
        }
    }

    /**
     * Soft deletion of the podcast with current id
     *
     * @param $id
     * @return bool
     */
    public function destroy($id)
    {
        if(!$podcast = Podcast::find($id)) {
            return $this->response->error('Podcast with this id doesn\'t exist!', 404);
        }
        try {
            $podcast->delete();
            return $this->response->array(Podcast::with('comments')->get()->toArray());
        } catch (\Exception $exception) {
            return $this->response->error($exception->getMessage(), 500);
        }
    }

    /**
     * Switching status to published for podcast with current id
     *
     * @param $podcast_id
     */
    public function approvePodcast($podcast_id)
    {
        if(!$podcast = Podcast::with('comments')->find($podcast_id)) {
            return $this->response->error('Podcast with this id doesn\'t exist!', 404);
        }
        if($podcast->status === 'published') {
            return $this->response->error('Podcast already has status of "published"', 422);
        }
        try {
            $podcast->status = 'published';
            $podcast->save();
            return $this->response->array($podcast->fresh()->toArray());
        } catch (\Exception $exception) {
            return $this->response->error($exception->getMessage(), 500);
        }
    }


    /**
     * Storing image to local storage
     *
     * @param $image (base64 format)
     * @return string
     */
    private function generateImage($image)
    {
        $image_name = 'image-'. time(). '.jpeg';
        $path = storage_path() . '/app/public/images/'.$image_name;
        Image::make(file_get_contents($image))->save($path);

        return $path;
    }
}