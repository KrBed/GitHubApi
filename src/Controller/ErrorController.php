<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ErrorController extends AbstractController
{
    /**
     * @param string $error
     * @return Response
     * @Route ("/exception",name="exception")
     */
    public function show(string $error): Response
    {
        return $this->render('exception/show.html.twig', ['error' => $error]);
    }

}