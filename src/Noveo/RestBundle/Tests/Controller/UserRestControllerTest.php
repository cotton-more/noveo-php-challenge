<?php


use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;

class UserRestControllerTest extends WebTestCase
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
     * @test
     */
    public function it_should_get_all_users()
    {
        $this->client->request('GET', '/api/v1/users/');
        $resp = $this->client->getResponse();

        $json = json_decode($resp->getContent(), true);

        static::assertEquals(Response::HTTP_OK, $resp->getStatusCode());
        static::assertArrayHasKey('users', $json);
    }

    /**
     * @test
     */
    public function it_should_get_one_user()
    {
        $this->client->request('GET', '/api/v1/users/1/');
        $resp = $this->client->getResponse();

        $json = json_decode($resp->getContent(), true);

        static::assertEquals(Response::HTTP_OK, $resp->getStatusCode());
        static::assertArrayHasKey('user', $json);
        static::assertEquals(1, $json['user']['id']);
    }

    /**
     * @test
     */
    public function it_should_create_user()
    {
        $faker = Faker\Factory::create();

        $userData = [
            'email' => $faker->email,
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'group' => 1,
        ];

        $this->client->request('POST', '/api/v1/users/', $userData);
        $resp = $this->client->getResponse();

        $json = json_decode($resp->getContent(), true);

        static::assertEquals(Response::HTTP_CREATED, $resp->getStatusCode());
        static::assertArrayHasKey('user', $json);
    }

    /**
     * @test
     */
    public function it_should_modify_user()
    {
        $faker = Faker\Factory::create();

        $userData = [
            'email' => $faker->email,
            'first_name' => $faker->firstName,
        ];

        $this->client->request('PATCH', '/api/v1/users/1/', $userData);
        $resp = $this->client->getResponse();

        static::assertEquals(Response::HTTP_NO_CONTENT, $resp->getStatusCode());
    }
}