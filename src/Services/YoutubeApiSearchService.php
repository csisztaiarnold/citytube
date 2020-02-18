<?php

namespace Drupal\citytube\Services;

use \Drupal\Core\Config\ConfigFactory;

/**
 * Class YoutubeApiSearchService
 *
 * @package \Drupal\citytube\Services
 */
class YoutubeApiSearchService {

  /**
   * The configuration factory.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  private $configFactory;

  /**
   * The Google API search endpoint url.
   *
   */
  private $googleApiSearchEndpointUrl = 'https://www.googleapis.com/youtube/v3/search';

  /**
   * YoutubeApiSearchService constructor.
   *
   * @param  \Drupal\Core\Config\ConfigFactory  $configFactory
   * The configuration factory.
   *
   */
  public function __construct(ConfigFactory $configFactory) {
    $this->configFactory = $configFactory;
  }

  /**
   * Populate the nodes with the video data.
   *
   */
  public function populateNodes() {
    /*
    // Creates a node.
    $node_storage = $this->entityTypeManager->getStorage('node');
    $node = $node_storage->create([
      'type' => 'citytube_video',
      'title' => 'Just a citytube video',
      'body' => 'This is the body',
      'field_video_id' => 'video id',
      'field_channel' => 'channel name',
      'field_channel_id' => 'channel id',
      'field_thumbnail_url' => 'thumbnail_url',
      'field_published' => '2012-04-18T12:24:16',
    ]);
    $node_storage->save($node);
    */
  }

  /**
   * Gets the video list from the YouTube API.
   *
   */
  public function getVideoList() {
    $config = $this->configFactory->get('citytube.settings');
    $yt_api_url = $config->get('yt_api_url');
    $yt_api_key = $config->get('yt_api_key');
    $max_results = $config->get('max_results');
    if (!empty($config->get('locations'))) {
      $video_list = [];
      foreach (explode("\n", $config->get('locations')) as $location) {
        $location_data = explode('|', $location);

        $search = [
          'q' => '',
          'part' => 'snippet',
          'type' => 'video',
          'order' => 'date',
          'maxResults' => !empty($max_results) ? $max_results : '30',
          'key' => $yt_api_key,
        ];

        $search_by_keyword = [
          'q' => $location_data[0],
        ];

        $search_by_location = [
          'location' => $location_data[1],
          'locationRadius' => trim($location_data[2]) . 'km',
        ];

        $search_query_by_keyword = $this->keyValueImplode(array_merge($search, $search_by_keyword));
        $search_query_by_location = $this->keyValueImplode(array_merge($search, $search_by_location));

        $video_list[$location_data[0]] = [
          'results_by_keyword' => $this->curlCall($yt_api_url . '?' . $search_query_by_keyword),
          'results_by_location' => $this->curlCall($yt_api_url . '?' . $search_query_by_location),
        ];
      }
      return $video_list;
    }
  }


  /**
   * The CURL call.
   *
   * @param $url
   *
   * @return mixed
   */
  public function curlCall(string $url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_FAILONERROR, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
      // TODO: Log this error message.
      $error_msg = curl_error($ch);
    }
    curl_close ($ch);


    $data = json_decode($response);
    $value = json_decode(json_encode($data), TRUE);

    return $value;
  }

  /**
   * Implodes key and value of an array.
   *
   * @param  array  $array
   *
   * @return string
   */
  private function keyValueImplode(array $array): string {
    return implode('&', array_map(
      function ($v, $k) {
        if (is_array($v)) {
          return $k . '[]=' . implode('&' . $k . '[]=', $v);
        }
        else {
          return $k . '=' . $v;
        }
      },
      $array,
      array_keys($array)
    ));
  }

}
