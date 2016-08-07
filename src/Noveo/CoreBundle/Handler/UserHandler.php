<?php

namespace Noveo\CoreBundle\Handler;

use Doctrine\ORM\EntityManager;
use Noveo\CoreBundle\Entity\User;
use Noveo\CoreBundle\Repository\UserRepository;

class UserHandler
{
    /**
     * @var EntityManager
     */
    private $em;
    private $userEntityClass;

    /**
     * @var UserRepository
     */
    private $repository;

    /**
     * UserHandler constructor.
     * @param EntityManager $em
     * @param $userEntityClass
     */
    public function __construct(EntityManager $em, $userEntityClass)
    {

        $this->em = $em;
        $this->userEntityClass = $userEntityClass;

        $this->repository = $em->getRepository($userEntityClass);
    }

    /**
     * @param array $param
     * @return User
     */
    public function createUser(array $param = null)
    {
        /** @var User $class */
        $class = $this->userEntityClass;

        $email = $param['email'];
        unset($param['email']);

        return $class::createActive($email, $param);
    }

    /**
     * @param User $user
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveUser(User $user)
    {
        $this->em->persist($user);
        $this->em->flush();
    }

    public function all()
    {
        $queryBuilder = $this->repository->createQueryBuilder('u')->where('u.state = :state')
            ->setParameter('state', User::STATE_ACTIVE);

        return $queryBuilder->getQuery()->getResult();
    }

    public function find($userId, $force = false)
    {
        $queryBuilder = $this->repository->createQueryBuilder('u')->where('u.id = :id')->setParameter('id', $userId);

        if ($force) {
            $queryBuilder->where('u.state = :state')->setParameter('state', User::STATE_ACTIVE);
        }

        return $queryBuilder->getQuery()->getOneOrNullResult();
    }
}