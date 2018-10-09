<?php
namespace App\API\V1\Transformers;

use League\Fractal\TransformerAbstract;

class PodcastTransformer extends TransformerAbstract
{
    public function transform($podcast)
    {
        return [
            'id' => (int) $podcast->id,
            'name' => $podcast->name,
            'description' => $podcast->description,
            'marketing_url' => $podcast->marketing_url,
            'feed_url' => $podcast->feed_url,
            'image' => $podcast->image,
        ];
    }
}