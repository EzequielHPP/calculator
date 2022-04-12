<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{

    /**
     * Render the Index File to show the calculator
     *
     * @Route("/", name="homepage", methods={"GET","HEAD"})
     * @return Response
     */
    final public function getIndex(): Response
    {
        return $this->render('homepage.html.twig');
    }

}
