<?php

namespace App\Controller;

use Exception;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class GithubAuthorizationController extends AbstractController
{
    /**
     * @Route("/connect/github", name="connect_github_start")
     * @param ClientRegistry $clientRegistry
     * @return RedirectResponse
     */
    public function connectAction(ClientRegistry $clientRegistry)
    {
        $client = $clientRegistry
            ->getClient('github') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect([
                'user', 'repo' // the scopes you want to access
            ], []);
        // will redirect to Github!
        return $client;
    }

    /**
     * After going to Github, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     *
     *
     * @param Request $request
     * @Route ("/auth" , name="auth")
     */
    public function connectCheckAction(Request $request)
    {
        if($request->query->get('error')){
          return  $this->render('exception/show.html.twig',['error'=>$request->query->get('error_description')]);
        }
    }

    /**
     * @Route ("/logout", name="logout")
     */
    public function logout(){

        throw new Exception('Don\'t forget to activate logout in security.yaml');
    }

}