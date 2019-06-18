<?php

namespace Drupal\d8_cache\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Cache\Cache;
use Drupal\user\Entity\User;

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

  	$userName = \Drupal::currentUser()->getAccountName();
    $cacheTags = User::load(\Drupal::currentUser()->id())->getCacheTags();

    return array(
      '#theme' => 'd8cacheblock',
            '#title' => 'User Detail',
            '#nodetitle' => $userName,
            '#cache' => [
              // We need to use entity->getCacheTags() instead of hardcoding "user:2"(where 2 is uid) or trying to memorize each pattern.
              'tags' => $cacheTags,
      ]
    );
  }

}
