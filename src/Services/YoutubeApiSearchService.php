<?php

namespace Drupal\citytube\Services;

use \Drupal\Core\Config\ConfigFactory;
use \Drupal\Core\Entity\EntityTypeManagerInterface;
use \Drupal\Core\State\StateInterface;

/**
 * Class YoutubeApiSearchService
 *
 * @package \Drupal\citytube\Services
 */
class YoutubeApiSearchService {

  /**
   * The configuration factory.
   *
   * @var ConfigFactory
   */
  private $configFactory;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The state storage service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * YoutubeApiSearchService constructor.
   *
   * @param ConfigFactory $configFactory
   * The configuration factory.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   * The entity type manager.
   * @param \Drupal\Core\State\StateInterface $state
   * The state storage service.
   */
  public function __construct(ConfigFactory $configFactory, EntityTypeManagerInterface $entityTypeManager, StateInterface $state) {
    $this->configFactory = $configFactory;
    $this->entityTypeManager = $entityTypeManager;
    $this->state = $state;
  }

  /**
   * Creates a term if it doesn't exists.
   *
   * @param string $term_name
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function populateTerms(string $term_name): void {
    // Term exists?
    $term = $this->entityTypeManager->getStorage('taxonomy_term')
      ->loadByProperties([
          'name' => $term_name,
        ]
      );
    if (count($term) === 0) {
      $term = $this->entityTypeManager->getStorage('taxonomy_term')
        ->create([
          'vid' => 'cities',
          'name' => $term_name,
        ]);
      $term->save();
    }
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
    $yt_api_key = $this->state->get('yt_api_key');
    $yt_api_url = $config->get('yt_api_url');
    $max_results = $config->get('max_results');
    if (!empty($config->get('locations'))) {
      $video_list = [];
      foreach (explode("\n", $config->get('locations')) as $location) {
        $location_data = explode('|', $location);
        $city_name = $location_data[0];
        $this->populateTerms($city_name);
        $search = [
          'q' => '',
          'part' => 'snippet',
          'type' => 'video',
          'order' => 'date',
          'maxResults' => !empty($max_results) ? $max_results : '30',
          'key' => $yt_api_key,
        ];

        $search_by_keyword = [
          'q' => $city_name,
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
    curl_setopt($ch, CURLOPT_FAILONERROR, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
      // TODO: Log this error message.
      $error_msg = curl_error($ch);
    }
    curl_close($ch);


    $data = json_decode($response);
    $value = json_decode(json_encode($data), TRUE);

    return $value;
  }

  /**
   * Implodes key and value of an array.
   *
   * @param array $array
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
