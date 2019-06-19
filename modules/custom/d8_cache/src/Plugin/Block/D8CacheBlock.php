<?php

namespace Drupal\d8_cache\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Cache\Cache;
use Drupal\user\Entity\User;
use Drupal\node\Entity\Node;

/**
 * Provides a 'D8CacheBlock' Block.
 *
 * @Block(
 *   id = "d8_cache_block",
 *   admin_label = @Translation("D8 Cache Block"),
 *   category = @Translation("D8 Cache Block"),
 * )
 */
class D8CacheBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */

  public function build() {

   $build = array();

    $query = \Drupal::entityQuery('node')
      ->condition('status', 1) //published or not
      ->condition('type', 'article') //content type
      ->sort('changed' , 'DESC')
      ->range(0, 1);
    $nids = $query->execute();

    foreach ($nids as $nidkey => $nidvalue) {
      $node = Node::load($nidvalue);
      $title = $node->label();
    }


    kint($this);

    if ($nids) {
      $build = array(
        '#type' => 'markup',
        '#markup' => '<p>' . $title . '<p>',
        '#cache' => array(
          //'tags' => ['node_list'],
          //'contexts' => ['url.path.is_front'],
          //'max-age' => 10, 
          //'tags' => $this->getCacheTags(),
          //'contexts' => $this->getCacheContexts(),
        ),
      );
    }
    else{
      $build = array(
        '#type' => 'markup',
        '#markup' => '<p>test comming<p>',
      );
    }

    return $build;
  }

}
