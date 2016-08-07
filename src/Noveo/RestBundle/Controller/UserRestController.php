<?php

namespace Noveo\RestBundle\Controller;


use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserRestController extends FOSRestController
{
    public function getUsersAction()
    {
        $users = $this->get('noveo_core.user_handler')->all();

        $view = View::create([
            'users' => $users,
        ], Response::HTTP_OK);

        return $this->handleView($view);
    }

    public function getUserAction($id)
    {
        $user = $this->get('noveo_core.user_handler')->find($id);

        $view = View::create([
            'user' => $user,
        ], Response::HTTP_OK);

        return $this->handleView($view);
    }

    public function postUserAction(Request $request)
    {
        $user = $this->get('noveo_core.user_handler')->post($request->request->all());

        $view = View::create([
            'user' => $user,
        ], Response::HTTP_CREATED);

        return $this->handleView($view);
    }

    public function patchUserAction(Request $request, $id)
    {
        $user = $this->get('noveo_core.user_handler')->find($id);

        $this->get('noveo_core.user_handler')->patch($user, $request->request->all());

        $view = View::create(null, Response::HTTP_NO_CONTENT);

        return $this->handleView($view);
    }
}
