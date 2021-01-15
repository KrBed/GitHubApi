<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ErrorController extends AbstractController
{
    /**
     * @param string $error
     * @return Response
     */
    public function show(string $error): Response
    {
        return $this->render('exception/show.html.twig', ['error' => $error]);
    }

}