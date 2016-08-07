<?php

namespace Noveo\CoreBundle\Handler;

use Doctrine\ORM\EntityManager;
use Noveo\CoreBundle\Entity\Group;
use Noveo\CoreBundle\Entity\User;
use Noveo\CoreBundle\Repository\GroupRepository;
use Noveo\RestBundle\Form\GroupType;
use Symfony\Component\Form\FormFactoryInterface;


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
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * GroupHandler constructor.
     * @param EntityManager $em
     * @param $groupEntityClass
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(EntityManager $em, $groupEntityClass, FormFactoryInterface $formFactory)
    {

        $this->em = $em;
        $this->groupEntityClass = $groupEntityClass;

        $this->repository = $em->getRepository($groupEntityClass);
        $this->formFactory = $formFactory;
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

    public function patch(Group $group, $data)
    {
        return $this->processForm($data, $group, 'PATCH');
    }

    /**
     * @param Group $group
     * @param User[] $users
     */
    public function resetUsers(Group $group, array $users)
    {
        $group->getUsers()->clear();
        foreach ($users as $user) {
            $group->addUser($user);
            $user->setGroup($group);

            $this->em->persist($user);
        }

        $this->em->persist($group);
        $this->em->flush();
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
        $form = $this->formFactory->create(GroupType::class, $user, ['method' => $method]);
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