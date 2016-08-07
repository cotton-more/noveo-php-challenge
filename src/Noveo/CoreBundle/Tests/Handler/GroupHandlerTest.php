<?php

use Faker\Generator;
use Noveo\CoreBundle\Handler\GroupHandler;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GroupHandlerTest extends KernelTestCase
{
    /**
     * @var GroupHandler
     */
    private $groupHandler;

    /**
     * @var Generator
     */
    private $faker;

    public function setUp()
    {
        parent::setUp();

        static::bootKernel();

        $this->groupHandler = static::$kernel->getContainer()->get('noveo_core.group_handler');

        $this->faker = Faker\Factory::create();
    }

    /**
     * @test
     */
    public function it_should_create_group()
    {
        $groupName = $this->faker->unique()->word;

        $group = $this->groupHandler->createGroup($groupName);

        $this->groupHandler->saveGroup($group);

        static::assertNotEmpty($group->getId());
    }

    /**
     * @test
     */
    public function it_should_get_users()
    {
        $groups = $this->groupHandler->all();

        static::assertGreaterThanOrEqual(1, count($groups));
    }
}