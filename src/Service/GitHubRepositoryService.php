<?php

namespace App\Service;


use App\Entity\Repository;
use Psr\Container\ContainerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GitHubRepositoryService extends AbstractApi
{

    private $loggedUserReposUrl = GitHubConfig::GITHUB_BASE_URL . GitHubConfig::LOGGED_USER_REPO_URL;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(HttpClientInterface $client, ContainerInterface $container)
    {
        $this->container = $container;
        parent::__construct($client);
    }

    /**
     * @return ResponseInterface
     */
    public function getLoggedUserRepos()
    {
        $token = $this->container->get('session')->get('access_token');
        $response = $this->get($this->loggedUserReposUrl,
            ['headers' => GitHubConfig::getGithubHeaders(), 'auth_bearer' => $token]);
        return $response;
    }

    /**
     * @param Repository $repo
     * @return ResponseInterface
     */
    public function createRepoForLoggedUser(Repository $repo)
    {
        $token = $this->container->get('session')->get('access_token');
        $response = $this->post($this->loggedUserReposUrl,
            ['headers' => GitHubConfig::getGithubHeaders(), 'auth_bearer' => $token, 'json' => $repo]);
        return $response;
    }
}