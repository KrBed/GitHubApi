<?php


namespace App\Service;


use Symfony\Contracts\HttpClient\HttpClientInterface;

class AbstractApi
{
    protected $client;

    public const METHOD_GET = 'GET';
    public const METHOD_POST = 'POST';

    /**
     * AbstractApi constructor.
     * @param HttpClientInterface|null $client
     */
    public function __construct(HttpClientInterface $client )
    {
        $this->client = $client;
    }


    public function get($url, $query = [])
    {
        if (!$this->client) {
            throw new \RuntimeException(printf(
                'Did you forget to pass the http-client in the constructor of %s?', get_class($this)
            ));
        }
        $response = $this->client->request(self::METHOD_GET, $url, $query);
        $statusCode = $response->getStatusCode();

        return $response;

    }

    public function post($url, $body = [])
    {
        if (!$this->client) {
            throw new \RuntimeException(printf(
                'Did you forget to pass the http-client in the constructor of %s?',
                get_class($this)
            ));
        }
        $response = $this->client->request(self::METHOD_POST, $url, $body);
        $statusCode = $response->getStatusCode();

        return $response;
    }
}