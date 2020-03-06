<?php

namespace Drupal\citytube\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'TermMenu' Block.
 *
 * @Block(
 *   id = "term_menu_block",
 *   admin_label = @Translation("Term Menu block"),
 *   category = @Translation("Term Menu Block"),
 * )
 */
class TermMenuBlock extends BlockBase implements ContainerFactoryPluginInterface
{

  /**
   * The entity type manager.
   *
   * @var EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * TermMenuBlock constructor.
   *
   * @param EntityTypeManagerInterface $entityTypeManager
   * The entity type manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, EntityTypeManagerInterface $entityTypeManager)
  {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->entityTypeManager = $entityTypeManager;
  }

  /**
   * @inheritDoc
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition)
  {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build()
  {
    $term_menu_data = 'term menu data';
    return [
      '#theme' => 'term_menu_block',
      '#term_menu_data' => $term_menu_data,
    ];
  }

}
