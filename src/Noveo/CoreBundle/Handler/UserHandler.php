<?php

namespace Noveo\CoreBundle\Handler;

use Doctrine\ORM\EntityManager;
use Noveo\CoreBundle\Entity\User;
use Noveo\CoreBundle\Repository\UserRepository;
use Noveo\RestBundle\Form\CreateUserType;
use Symfony\Component\Form\FormFactoryInterface;

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
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * UserHandler constructor.
     * @param EntityManager $em
     * @param $userEntityClass
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(EntityManager $em, $userEntityClass, FormFactoryInterface $formFactory)
    {

        $this->em = $em;
        $this->userEntityClass = $userEntityClass;

        $this->repository = $em->getRepository($userEntityClass);
        $this->formFactory = $formFactory;
    }



    /**
     * @return User
     */
    public function createUser()
    {
        /** @var User $class */
        $class = $this->userEntityClass;

        return $class::createActive();
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

    public function all($ids = null)
    {

        $queryBuilder = $this->repository->createQueryBuilder('u')->where('u.state = :state')
            ->setParameter('state', User::STATE_ACTIVE);

        if (null !== $ids) {
            $queryBuilder->andWhere('u.id IN (:ids)');
            $queryBuilder->setParameter('ids', $ids);
        }

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

    public function post(array $data)
    {
        $user = $this->createUser();

        return $this->processForm($data, $user);

    }

    public function patch(User $user, $data)
    {
        return $this->processForm($data, $user, 'PATCH');
    }

    /**
     * @param array $data
     * @param $user
     * @param string $method
     * @return mixed
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     * @throws \RuntimeException
     * @throws \Symfony\Component\Form\Exception\AlreadySubmittedException
     * @throws \Symfony\Component\OptionsResolver\Exception\InvalidOptionsException
     */
    private function processForm(array $data, $user, $method = 'POST')
    {
        $form = $this->formFactory->create(CreateUserType::class, $user, ['method' => $method]);
        $form->submit($data, 'PATCH' !== $method);

        if ($form->isValid()) {
            $user = $form->getData();

            $this->em->persist($user);
            $this->em->flush($user);

            return $user;
        }

        throw new \RuntimeException('Invalid submitted data');
    }
}