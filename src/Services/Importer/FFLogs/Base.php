<?php


namespace App\Services\Importer\FFLogs;


use App\Services\AbstractService;

class Base extends AbstractService
{
    protected $base_url = [
        'en' => 'https://www.fflogs.com:443/v1',
        'de' => 'https://de.fflogs.com:443/v1',
        'fr' => 'https://fr.fflogs.com:443/v1',
        'jp' => 'https://ja.fflogs.com:443/v1'
    ];
    protected $endpoint = '';
    protected $params = [];

    public function setParam(array $params)
    {
        foreach ($params as $key => $value)
        {
            $this->params[$key] = $value;
        }
        return $this;
    }

    public function generateUrl(string $lang='en')
    {
        $endpoint = $this->endpoint;

        $params = $this->params;
        $params['api_key'] = $_ENV['FFLOGS_KEY'];
        foreach ($params as $param => $value)
        {

            // check for in route parameter and replace them
            if (substr_count($endpoint, '${'.$param.'}') >= 1) {
                $endpoint = str_replace('${'.$param.'}', $value, $endpoint);
                unset($params[$param]);
            }
            else {
                $params[$param] = $param.'='.$value;
            }
        }
        $params = implode('&', $params);

        return $this->base_url[$lang].$endpoint.'?'.$params;
    }
}