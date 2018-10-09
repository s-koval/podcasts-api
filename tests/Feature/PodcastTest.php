<?php

namespace Tests\Feature;

use App\Podcast;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class PodcastTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * It fetching podcasts by given status.
     *
     * @return void
     */
    public function testGetListOfPodcastsByStatus()
    {
        factory(Podcast::class, 5)->create();
        factory(Podcast::class, 3)->create(['status' => 'published']);
        $response = $this->get('api/podcasts/review');
        $response->assertStatus(200);
        $data = json_decode($response->content())->data;
        $this->assertEquals(5, count($data));

        $response = $this->get('api/podcasts/published');
        $response->assertStatus(200);
        $data = json_decode($response->content())->data;
        $this->assertEquals(3, count($data));
    }

    /**
     * It getting single podcast by given id.
     *
     * @return void
     */
    public function testGetCurrentPodcastById()
    {
        $podcast = factory(Podcast::class)->create([
            'name' => 'test name',
            'description' => 'test description',
            'feed_url' => 'http://feed-url.com'
        ]);
        $response = $this->get("api/podcast/{$podcast->id}");
        $response->assertStatus(200);
        $data = json_decode($response->content());
        $this->assertEquals($data->name, $podcast->name);
        $this->assertEquals($data->description, $podcast->description);
        $this->assertEquals($data->feed_url, $podcast->feed_url);
    }

    /**
     * It creating new podcast
     *
     * @return void
     */
    public function testCreateNewPodcast()
    {
        $response = $this->post("api/podcast/create", [
            'name' => 'test name',
            'description' => 'test description',
            'feed_url' => 'http://feed-url.com'
        ]);
        $response->assertStatus(200);
        $this->assertEquals(1, Podcast::all()->count());
        $data = json_decode($response->content());
        $this->assertEquals('test name', $data->name);
        $this->assertEquals('test description', $data->description);
        $this->assertEquals('http://feed-url.com', $data->feed_url);
    }

    /**
     * It updating existing podcast by given id
     *
     * @return void
     */
    public function testUpdateCurrentPodcastById()
    {
        $podcast = factory(Podcast::class)->create([
            'name' => 'test name',
            'description' => 'test description',
            'feed_url' => 'http://feed-url.com'
        ]);
        $response = $this->patch("api/podcast/{$podcast->id}", [
            'name' => 'updated name',
            'description' => 'updated description',
            'feed_url' => 'http://feed-updated-url.com'
        ]);

        $response->assertStatus(200);
        $data = json_decode($response->content());
        $this->assertEquals('updated name', $data->name);
        $this->assertEquals('updated description', $data->description);
        $this->assertEquals('http://feed-updated-url.com', $data->feed_url);
    }

    /**
     * It soft deleting existing podcast by given id
     *
     * @return void
     */
    public function testDeleteCurrentPodcastById()
    {
        $podcast = factory(Podcast::class)->create([
            'name' => 'test name',
            'description' => 'test description',
            'feed_url' => 'http://feed-url.com'
        ]);
        $this->assertEquals(1, Podcast::all()->count());

        $response = $this->delete("api/podcast/{$podcast->id}");
        $response->assertStatus(200);
        $this->assertEquals(0, Podcast::all()->count());
        $this->assertEquals(1, Podcast::withTrashed()->get()->count());
    }

    /**
     * It approving existing podcast by given id
     *
     * @return void
     */
    public function testApproveCurrentPodcastById()
    {
        $podcast = factory(Podcast::class)->create([
            'name' => 'test name',
            'description' => 'test description',
            'feed_url' => 'http://feed-url.com'
        ]);
        $this->assertEquals('review', Podcast::find($podcast->id)->status);
        $response = $this->post("api/approve-podcast/{$podcast->id}");
        $response->assertStatus(200);
        $data = json_decode($response->content());
        $this->assertEquals('published', $data->status);
    }
}
