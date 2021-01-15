<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\Request;

class GitHubConfig
{
    public const GITHUB_BASE_URL = 'https://api.github.com/';
    public const LOGGED_USER_REPO_URL = 'user/repos';
    const authorizeURL = 'https://github.com/login/oauth/authorize';
    const tokenURL = 'https://github.com/login/oauth/access_token';
    public const STATUS_CODE_OK = 200;
    public const STATUS_CODE_CREATED = 201;

    /**
     * @return string[]
     */
    public static function getGithubHeaders(): array
    {
        return ['accept' => 'application/vnd.github.v3+json'];
    }

    /**
     * @param Request $request
     * @return array
     */
    public static function getDataToObtainToken(Request $request): array
    {
        $params = array(
            'client_id' => $_ENV['OAUTH_GITHUB_CLIENT_ID'],
            'client_secret' => $_ENV['OAUTH_GITHUB_CLIENT_SECRET'],
            'code' => $request->query->get('code'),
            'state' => $request->query->get('state'),
            'redirect_uri' => $_ENV['REDIRECT_URI'],
        );
        return $params;
    }

}