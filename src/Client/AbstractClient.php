<?php

namespace BigPropertyData\Police\Client;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

class AbstractClient
{
    /**
     * @var string API endpoint
     */
    protected $api = 'https://data.police.uk/api/';

    /**
     * @var \GuzzleHttp\Client a client to make requests to the API
     */
    protected $guzzle;

    /**
     * Makes a Url request and returns its response.
     *
     * @param string $endpoint     the command
     * @param string $method  the method 'get' or 'post'
     * @param array  $params  the parameters to be bound to the call
     * @param array  $options the options to be attached to the client
     *
     * @return PromiseInterface
     */
    protected function request($endpoint, $method = 'get', $params = [], $options = [])
    {
        $promise = $this->getGuzzleClient()->requestAsync(
            $method,
            $this->api . $endpoint,
            array_merge(['query' => $params], $options)
        );

        return $promise->then(function (ResponseInterface $response) {
            if ($response->getStatusCode() === 200) {
                return json_decode($response->getBody()->getContents());
            }

            return null;
        });
    }

    /**
     * Returns an option from an array. If not set return default value.
     *
     * @param array  $options
     * @param string $param
     * @param mixed  $default
     *
     * @return mixed|null
     */
    protected function getParamValue($options, $param, $default = null)
    {
        return isset($options[$param]) ? $options[$param] : $default;
    }

    /**
     * Build the params from a number of different location types
     *
     * @param $location
     * @param array $params
     * @return array
     */
    protected function buildLocationParams($location, array $params)
    {
        if (is_string($location)) {
            $params['location_id'] = $location;
        } else if (is_array($location[0])) {
            $params['poly'] = implode(
                ':',
                array_map(
                    function (array $latLng) {
                        return implode(',', $latLng);
                    },
                    $location
                )
            );
        } else {
            list($params['lat'], $params['lng']) = $location;
        }

        return $params;
    }

    /**
     * Returns the guzzle client.
     *
     * @return \GuzzleHttp\Client
     */
    protected function getGuzzleClient()
    {
        if ($this->guzzle == null) {
            $this->guzzle = new HttpClient();
        }

        return $this->guzzle;
    }
}
