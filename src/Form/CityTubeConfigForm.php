<?php

/**
 * @file
 * Contains \Drupal\citytube\Form\CityTubeConfigForm.
 */

namespace Drupal\citytube\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\State\StateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CityTubeConfigForm
 *
 * @package Drupal\citytube\Form
 */
class CityTubeConfigForm extends ConfigFormBase {

  /**
   * The Drupal state storage service.
   *
   * @var \Drupal\Core\State\StateInterface
   */
  protected $state;

  /**
   * CityTubeConfigForm constructor.
   *
   * @param \Drupal\Core\State\StateInterface $state
   *   The state service.
   */
  public function __construct(StateInterface $state) {
    $this->state = $state;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('state')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'citytube_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);
    $config = $this->config('citytube.settings');

    $form['yt_api_url'] = [
      '#type' => 'textfield',
      '#title' => t('YouTube API URL'),
      '#default_value' => $config->get('yt_api_url') ?? 'https://www.googleapis.com/youtube/v3/search',
      '#required' => TRUE,
      '#description' => t('Visit https://developers.google.com/youtube/v3/docs/search/list#http-request for more information.'),
    ];

    $form['yt_api_key'] = [
      '#type' => 'textfield',
      '#title' => t('YouTube API key'),
      '#default_value' => $this->state->get('yt_api_key'),
      '#required' => TRUE,
      '#description' => t('To get your API key, check https://developers.google.com/youtube/v3/getting-started for more information.'),
    ];

    $form['max_results'] = [
      '#type' => 'textfield',
      '#title' => t('Max. results'),
      '#default_value' => $config->get('max_results'),
      '#description' => t('You could decrease this value after the first few API calls in case you make the call often or there are not too many new videos per day.'),
    ];

    $form['locations'] = [
      '#type' => 'textarea',
      '#title' => t('Locations (location_name|lat,lng|search_radius)'),
      '#default_value' => $config->get('locations'),
      '#required' => TRUE,
      '#description' => t('Every location should go to a new line. Location name, coordinates and search radius should be separated by the pipe character (|). Latitude and longitude should be separated by comma (,).'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('citytube.settings');
    $config->set('yt_api_url', $form_state->getValue('yt_api_url'));
    $config->set('max_results', $form_state->getValue('max_results'));
    $config->set('locations', $form_state->getValue('locations'));
    $config->save();
    $this->state->set('yt_api_key', $form_state->getValue('yt_api_key'));
    return parent::submitForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'citytube.settings',
    ];
  }

}
