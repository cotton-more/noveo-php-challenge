<?php

namespace Noveo\RestBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Noveo\CoreBundle\Entity\Group;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class GroupRestController extends FOSRestController
{
    public function getGroupsAction()
    {
        $groups = $this->get('noveo_core.group_handler')->all();

        $view = View::create([
            'groups' => $groups,
        ], Response::HTTP_OK);

        return $this->handleView($view);
    }

    public function patchGroupAction(Request $request, Group $group)
    {
        $this->get('noveo_core.group_handler')->patch($group, $request->request->all());

        $view = View::create(null, Response::HTTP_NO_CONTENT);

        return $this->handleView($view);
    }

    public function patchGroupUsersAction(Request $request, Group $group)
    {
        $users = $this->get('noveo_core.user_handler')->all($request->request->get('users'));
        $this->get('noveo_core.group_handler')->resetUsers($group, $users);

        $view = View::create(null, Response::HTTP_NO_CONTENT);

        return $this->handleView($view);
    }
}