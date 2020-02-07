<?php

/**
 * @file
 * Contains \Drupal\citytube\Form\CityTubeConfigForm.
 */

namespace Drupal\citytube\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class CityTubeConfigForm
 *
 * @package Drupal\citytube\Form
 */
class CityTubeConfigForm extends ConfigFormBase {

  /**
   * CityTubeConfigForm constructor.
   */
  public function __construct() {
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

    $form['yt_api_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('YouTube API key'),
      '#default_value' => $config->get('yt_api_key'),
      '#required' => TRUE,
    ];

    $form['locations'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Locations (location|lat,lang)'),
      '#default_value' => $config->get('locations'),
      '#required' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('citytube.settings');
    $config->set('yt_api_key', $form_state->getValue('yt_api_key'));
    $config->set('locations', $form_state->getValue('locations'));
    $config->save();

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
