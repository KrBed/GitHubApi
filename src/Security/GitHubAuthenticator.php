<?php


namespace App\Security;


use App\Entity\User;
use App\Service\GitHubConfig;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\Github;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class GitHubAuthenticator extends  SocialAuthenticator
{
    private $clientRegistry;
    private $em;
    private $router;

    public function __construct(ClientRegistry $clientRegistry, EntityManagerInterface $em, RouterInterface $router)
    {
        $this->clientRegistry = $clientRegistry;
        $this->em = $em;
        $this->router = $router;
    }



    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse(
        '/', // might be the site, where users choose their oauth provider
        Response::HTTP_TEMPORARY_REDIRECT);
    }

    public function supports(Request $request): bool
    {
        return $request->attributes->get('_route') === 'auth';
    }

    public function getCredentials(Request $request)
    {
        $client = $this->clientRegistry->getClient('github');

        $params = GitHubConfig::getDataToObtainToken($request);
        $credentials = $client->getAccessToken($params);
        $request->getSession()->set('access_token',$credentials->getToken());

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            // the exact class dependusers on which provider you're using
            /** @var Github $user */
            $user = $this->clientRegistry->getClient('github')
                ->fetchUserFromToken($credentials)->toArray();
            $existingUser = $this->em->getRepository(User::class)
            ->findOneBy(['gitHubId'=>$user['id']]);
            if($existingUser){


                return $existingUser;
            }
            $newUser = new User();
           $newUser->setGitHubId($user['id']);
           $newUser->setLogin($user['login']);
           $newUser->setAccessToken($credentials->getToken());
            $this->em->persist($newUser);

            $this->em->flush();

            return $newUser;

        } catch (IdentityProviderException $e) {
            // something went wrong!
            // probably you should return the reason to the user
            var_dump($e->getMessage());
            die;
        }
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $targetUrl = $this->router->generate('github_repository_list');

        return new RedirectResponse($targetUrl);
    }
}