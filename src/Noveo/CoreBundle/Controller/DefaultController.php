<?php

namespace Noveo\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('NoveoCoreBundle:Default:index.html.twig');
    }
}
