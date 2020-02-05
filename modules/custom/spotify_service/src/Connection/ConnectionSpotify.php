<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 2/4/20
 * Time: 5:03 PM
 */

namespace Drupal\spotify_service\Connection;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

/**
 * Connection services.
 */
class ConnectionSpotify {

    /**
     * The block manager.
     *
     * @var GuzzleHttp\Client
     */
    private $client;

    private $config;

    /**
     * ConnectionSpotify constructor.
     */
    public function __construct() {
        $this->client = \Drupal::httpClient();
        $this->config = \Drupal::config('spotify_service.settings');
    }

    /**
     * Return client request.
     */
    public function getClient() {

        $token = '';
        //try get token from spotify
        try {

            $end_point = $this->config->get('end_point') == NULL ? 'https://accounts.spotify.com/api/token' : $this->config->get('end_point');
            $client_id = $this->config->get('client_id') == NULL ? 'cb0ebf98b08b4070be8e914b5dc1d821' : $this->config->get('client_id');
            $client_secret = $this->config->get('client_secret') == NULL ? '6743b1a4ddc74e919778182287ae12b0' : $this->config->get('client_secret');

            $login = $this->client->request('POST', $end_point, [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => $client_id,
                    'client_secret' => $client_secret,
                ],
            ]);
            $token = json_decode($login->getBody()
                ->getContents())->access_token;
        } catch (RequestException $exc) {
            \Drupal::logger('spotify_service')
                ->info("Error login: <pre>@server</pre> ", [
                    '@server' => $exc->getMessage(),
                ]);
        }
        // Get config file with bearer token
        return new Client([
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ],
            'timeout' => '30',
        ]);
    }

    /**
     * Get data from spotify rest by url .
     */
    public function getData($url) {

        $response = new \stdClass();
        $response->message = NULL;
        $response->data = NULL;
        $client = $this->getClient();

        //try get json data
        try {
            $request = $client->request('GET', $url);
            if ($request->getStatusCode() === 200) {
                $response->data = $request->getBody()->getContents();
                $response->message = "success";
            }
            else {
                $response->message = "error";
            }
        } catch (RequestException $exc) {
            \Drupal::logger('spotify_service')
                ->info("Could not connect to the service, message: <pre>@server</pre> ", [
                    '@server' => $exc->getMessage(),
                ]);
        }
        return $response;
    }


}