<?php
/**
 * @file
 * Contains \Drupal\citytube\Controller\CityTubeController.
 */

namespace Drupal\citytube\Controller;

use \Drupal\citytube\Services\YoutubeApiSearchService;
use \Drupal\Core\Controller\ControllerBase;
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
  private $youtubeApiSearchService;

  /**
   * CityTube constructor.
   *
   * @param  \Drupal\citytube\Services\YoutubeApiSearchService  $youtubeApiSearchService
   * The weather service.
   */
  public function __construct(YoutubeApiSearchService $youtubeApiSearchService) {
    $this->youtubeApiSearchService = $youtubeApiSearchService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $youtubeApiSearchService = $container->get('citytube.youtubeapisearch_services');
    return new static($youtubeApiSearchService);
  }

  /**
   * Shows content.
   *
   */
  public function content() {
    return [];
  }

  /**
   * Calls the API manually.
   *
   */
  public function manualApiCallAction() {
    return [];
  }

}
