<?php
/**
 * Created by PhpStorm.
 * User: edwin
 * Date: 2/4/20
 * Time: 5:03 PM
 */

namespace Drupal\spotify_service\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\spotify_service\Connection\ConnectionSpotify;

/**
 * Class SpotifyController.
 *
 * @package Drupal\spotify_service\Controller
 */
class SpotifyController extends ControllerBase {



    /**
     * Function render.
     *
     */
    public function render() {

        $conn = new ConnectionSpotify();

        $config = \Drupal::config('spotify_service.settings');

        $api = $config->get('end_point_api') == NULL ? 'https://api.spotify.com/v1' : $config->get('end_point_api');

        //get json data from releases url
        $data = $conn->getData($api.'/browse/new-releases?country=SE&limit=12&offset=0');
        $albums = array();
        if($data->message == "success"){
           $albums=json_decode($data->data,true);
        }

        $build['render'] = [
            '#theme' => 'reder_sp',
            '#albums' => $albums["albums"]["items"],
        ];
        return $build;
    }


    /**
     * Function render.
     *
     */
    public function getTitle() {

        return "Ultimos Lanzamientos";
    }
}