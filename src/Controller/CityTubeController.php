<?php
/**
 * @file
 * Contains \Drupal\citytube\Controller\CityTubeController.
 */

namespace Drupal\citytube\Controller;

use \Drupal\citytube\Services\YoutubeApiSearchService;
use \Drupal\Core\Controller\ControllerBase;
use \Drupal\Core\Entity\EntityTypeManagerInterface;
use \Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CityTubeController
 *
 * @package Drupal\citytube\Controller
 */
final class CityTubeController extends ControllerBase {

  /**
   * The YouTube API Search service.
   *
   * @var \Drupal\citytube\Services\YoutubeApiSearchService
   */
  protected $youtubeApiSearchService;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * CityTube constructor.
   *
   * @param  \Drupal\citytube\Services\YoutubeApiSearchService  $youtube_api_search_service
   *   The YouTube Search API service.
   * @param  \Drupal\Core\Entity\EntityTypeManagerInterface  $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(YoutubeApiSearchService $youtube_api_search_service, EntityTypeManagerInterface $entity_type_manager) {
    $this->youtubeApiSearchService = $youtube_api_search_service;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('citytube.youtubeapisearch_services'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * Shows content.
   *
   */
  public function content() {
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
    ]);
    $node_storage->save($node);
    */
    return [
      '#markup' => '',
    ];
  }

  /**
   * Calls the API manually.
   *
   */
  public function manualApiCallAction() {
    return [];
  }

}
