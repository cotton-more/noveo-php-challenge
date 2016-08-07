<?php

use Faker\Generator;
use Noveo\CoreBundle\Handler\UserHandler;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserHandlerTest extends KernelTestCase
{
    /**
     * @var UserHandler
     */
    private $userHandler;

    /**
     * @var Generator
     */
    private $faker;

    public function setUp()
    {
        parent::setUp();

        static::bootKernel();

        $this->userHandler = static::$kernel->getContainer()->get('noveo_core.user_handler');

        $this->faker = Faker\Factory::create();
    }

    /**
     * @test
     */
    public function it_should_create_active_user()
    {
        $param = [
            'email' => $this->faker->email,
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
        ];

        $user = $this->userHandler->createUser($param);

        $this->userHandler->saveUser($user);

        static::assertNotEmpty($user->getId());
    }

    /**
     * @test
     */
    public function it_should_get_users()
    {
        $users = $this->userHandler->all();

        static::assertGreaterThanOrEqual(1, count($users));
    }

    /**
     * @test
     */
    public function it_should_get_active_user()
    {
        $userId = 1;
        $user = $this->userHandler->find($userId);

        static::assertEquals($userId, $user->getId());
    }
}