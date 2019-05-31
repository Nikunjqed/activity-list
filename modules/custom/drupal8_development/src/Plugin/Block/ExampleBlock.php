<?php

namespace Drupal\drupal8_development\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'drupal8 deveopment block' block.
 *
 * @Block(
 *   id = "drupal8_development_block",
 *   admin_label = @Translation("Drupal8 Development Custom Block"),
 *
 * )
 */
class ExampleBlock extends BlockBase {
  /**
   * {@inheritdoc}
   */
  public function build() {
    return [
      '#theme' => 'my_template',
      '#test_var' => $this->t('Test Value'),
    ];
  }
}