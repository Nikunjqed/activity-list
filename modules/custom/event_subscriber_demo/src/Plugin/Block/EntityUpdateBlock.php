<?php

namespace Drupal\event_subscriber_demo\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Controller\ControllerBase;
/**
 * Provides a 'EntityUpdateBlock' Block.
 *
 * @Block(
 *   id = "event_subscriber_demo",
 *   admin_label = @Translation("Custom Entity Update Block"),
 *   category = @Translation("Custom Entity Update Block"),
 * )
 */
class EntityUpdateBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */

  public function build() {
    $subTime = 'Subscriber Last Updated:'. \Drupal::state()->get('entity_update');
    $hookTime = '<BR>Hook Last Updated:'. \Drupal::state()->get('entity_update_hook');
    $entityId = \Drupal::state()->get('entity_update_id');
    $entityType = \Drupal::state()->get('entity_update_type');
    $cacheTag = $entityType .':'.$entityId;
    $output = $subTime.' '.$hookTime;
    return array(
        '#type' => 'markup',
        '#markup' => $output,
         '#cache' => array(
              'max-age' => 0,
              'tags' => [$cacheTag],
              //'contexts' => ['user'],
            ),
    );
  }

}