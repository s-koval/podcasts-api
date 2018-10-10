#podcasts-api

###Clone project from git:
run `git clone https://github.com/s-koval/podcasts-api.git`

###In the project folder run commands

- `cp .env.example .env`
- `php artisan key:generate`
- `mkdir storage/app/public/images`
- `php artisan storage:link`

Setup database in .env file and `run php artisan migrate --seed`


###API endpoints

GET request to api/podcasts/{status} with a status (review or published) will output a response with podcasts paginated collection with given status.

GET request to api/podcast/{id} with a podcast id will output a response with current podcast 

POST request to api/podcast/create with payload({
	name: 'name',
	description: 'description'
	feed_url: 'http://feed-url.com'
}) will create a new podcast in DB and return response with this podcast.

PATCH request to api/podcast/{id} with payload({
	name: 'name',
	description: 'description'
	feed_url: 'http://feed-url.com'
}) will update exist podcast with given id and return response with this podcast.

DELETE request to api/podcast/{id} with a podcast id will soft delete exist podcast with given id and return response with collection of all podcasts.

POST request to api/approve-podcast/{podcast_id} with a podcast id will switch podcast status from review to published and return response with this podcast.


POST request to api/comment/{podcast_id} with payload({
	author_name: 'John Doe',
	author_email: 'john@email.com'
	comment: 'Comment'
}) will create a new comment in DB for podcast with given id and return response with this podcast.

POST request to api/flag-comment/{comment_id} with a comment id will soft delete exist comment with given id and return response with podcast related to this commnet.



### For PHP UNIT TESTS

- Create new database for tests

- Update in .env file following variables:
    - DB_DATABASE_TESTING=
    - DB_USERNAME_TESTING=
    - DB_PASSWORD_TESTING=
    
- change in .env file DB_CONNECTION variable from mysql to testing
- run `php artisan config:cache`
- run `vendor/phpunit/phpunit/phpunit`