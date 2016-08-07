<?php


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;

class GroupRestControllerTest extends WebTestCase
{
    /**
     * @var Client
     */
    private $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * @-test
     */
    public function it_should_get_all_groups()
    {
        $this->client->request('GET', '/api/v1/groups/');
        $resp = $this->client->getResponse();

        $json = json_decode($resp->getContent(), true);

        static::assertEquals(Response::HTTP_OK, $resp->getStatusCode());
        static::assertArrayHasKey('groups', $json);
        static::assertGreaterThanOrEqual(1, $json['groups']);
    }

    /**
     * @-test
     */
    public function it_should_patch_group()
    {
        $faker = Faker\Factory::create();

        $groupData = [
            'name' => $faker->unique()->word,
        ];

        $this->client->request('PATCH', '/api/v1/groups/1/', $groupData);
        $resp = $this->client->getResponse();

        static::assertEquals(Response::HTTP_NO_CONTENT, $resp->getStatusCode());
    }

    /**
     * @test
     */
    public function it_should_update_users()
    {
        $users = [
            'users' => [
                11,
                12,
                13,
            ],
        ];

        $this->client->request('PATCH', '/api/v1/groups/2/users/', $users);
        $resp = $this->client->getResponse();

        static::assertEquals(Response::HTTP_NO_CONTENT, $resp->getStatusCode());
    }
}