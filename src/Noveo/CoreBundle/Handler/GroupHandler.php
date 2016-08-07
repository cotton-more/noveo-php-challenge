<?php

namespace Noveo\CoreBundle\Handler;

use Doctrine\ORM\EntityManager;
use Noveo\CoreBundle\Entity\Group;
use Noveo\CoreBundle\Repository\GroupRepository;


class GroupHandler
{
    /**
     * @var EntityManager
     */
    private $em;
    private $groupEntityClass;

    /**
     * @var GroupRepository
     */
    private $repository;

    /**
     * GroupHandler constructor.
     * @param EntityManager $em
     * @param $groupEntityClass
     */
    public function __construct(EntityManager $em, $groupEntityClass)
    {

        $this->em = $em;
        $this->groupEntityClass = $groupEntityClass;

        $this->repository = $em->getRepository($groupEntityClass);
    }

    /**
     * @param $groupName
     * @return Group
     */
    public function createGroup($groupName)
    {
        $class = $this->groupEntityClass;

        /** @var Group $group */
        $group = new $class;

        $group->setName($groupName);

        return $group;
    }

    /**
     * @param Group $group
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveGroup(Group $group)
    {
        $this->em->persist($group);
        $this->em->flush();
    }

    public function all()
    {
        $queryBuilder = $this->repository->createQueryBuilder('g');

        return $queryBuilder->getQuery()->getResult();
    }
}