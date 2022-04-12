<?php

namespace App\Controller;

use App\Service\v1\CalculatorService as v1CalculatorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CalculatorController extends AbstractController
{

    /**
     * Render the Index File to show the calculator
     *
     * @Route("/calculator", name="calculator_index", methods={"GET","HEAD"})
     * @return Response
     */
    final public function getIndex(): Response
    {
        return $this->render('calculator/index.html.twig');
    }


    /**
     * API Endpoint to calculate the expression
     *
     * @Route("/v1/calculator", name="calculator_post", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    final public function postCalculator(Request $request): Response
    {
        $calculation = (new v1CalculatorService($request))->calculate();
        return $this->json([
            'status' => $calculation !== 'NAN',
            'response' => $calculation
        ], $calculation !== 'NAN' ? '200' : '400');
    }
}
