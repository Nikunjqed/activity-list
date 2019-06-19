<?php

namespace Drupal\d8_cache\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Cache\Cache;
use Drupal\user\Entity\User;

/**
 * Class DefaultController.
 */
class DefaultController extends ControllerBase {

  /**
   * Cachemaxage.
   *
   * @return string
   *   Return Hello string.
   */
  public function cacheMaxAge() {
    return [
   '#markup' => t('Temporary by 10 seconds @time', ['@time' => time()]),
   '#cache' => [
       'max-age' => 10, // \Drupal\Core\Cache\Cache::PERMANENT,
     ]
   ];
  }
  /**
   * Cachecontextsbyurl.
   *
   * @return string
   *   Return Hello string.
   */
  public function cacheContextsByUrl() {
    return [
    '#markup' => t('WeKnow is the coolest @time', ['@time' => time()]),
    '#cache' => [
        'contexts' => ['url.query_args'],
      ]
  ];
  }
  /**
   * Cachetags.
   *
   * @return string
   *   Return Hello string.
   */
  public function cacheTags() {
    $userName = '';
    $cacheTags = User::load(\Drupal::currentUser()->id())->getCacheTags();
    $userName = \Drupal::currentUser()->getAccountName();
    
    return [
      '#markup' => t('WeKnow isssssss the coolest! Do you agree @userName ?', ['@userName' => $userName]),
      '#cache' => [
        // We need to use entity->getCacheTags() instead of hardcoding "user:2"(where 2 is uid) or trying to memorize each pattern.
        'tags' => $cacheTags,
        //'max-age' => 0,
      ]
    ];
  }



}
