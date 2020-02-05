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
 * Class ArtistController.
 *
 * @package Drupal\spotify_service\Controller
 */
class ArtistController extends ControllerBase {


    /**
     * Function render.
     * Receives String $id.
     */
    public function render($id) {

        $conn = new ConnectionSpotify();

        $config = \Drupal::config('spotify_service.settings');


        //get end point
        $api = $config->get('end_point_api') == NULL ? 'https://api.spotify.com/v1' : $config->get('end_point_api');


        //get json data from url  artist profile and albumbs
        $data = $conn->getData($api . '/artists/' . $id);
        $albums = $conn->getData($api . '/artists/' . $id.'/albums');
        $profile = [];
        if ($data->message == "success") {
            $profile = json_decode($data->data, TRUE);
        }
        if ($albums->message == "success") {
            $albums = json_decode($albums->data, TRUE);
        }

        $albums_a = [];

        //iterate albums
        foreach ($albums['items'] as $key => $value){

            $songs = $conn->getData($api . '/albums/' . $value['id'].'/tracks');

            $songs = json_decode($songs->data, TRUE);

            $albums_a[$key]['image'] = $value['images'][0]['url'];
            $albums_a[$key]['name'] = $value['name'];

            //only one song but you can iterate to bring more
            $albums_a[$key]['song'] = $songs['items'][0]['name'];


        }

        $build['render'] = [
            '#theme' => 'artist_sp',
            '#artist' => $profile,
            '#albums' => $albums_a,
        ];
        return $build;
    }
}