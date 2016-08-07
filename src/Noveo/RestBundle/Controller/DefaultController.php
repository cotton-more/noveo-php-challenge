<?php

namespace Noveo\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('NoveoRestBundle:Default:index.html.twig');
    }
}
