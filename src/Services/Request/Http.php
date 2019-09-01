<?php


namespace App\Services\Request;


use GuzzleHttp\Client;

class Http
{
    /**
     * @var string
     */
    private $url;
    /**
     * @var \Psr\Http\Message\ResponseInterface
     */
    private $response;

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function request()
    {
        $client = new Client();
        $this->response = $client->request(
            'GET',
            $this->url
        );
        return $this;
    }

    public function getBody()
    {
        return $this->response->getBody();
    }
}