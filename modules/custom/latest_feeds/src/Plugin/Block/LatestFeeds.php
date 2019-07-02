<?php

namespace Drupal\latest_feeds\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\NodeType;
/**
 * Provides a 'BlogsFeeds' Block.
 *
 * @Block(
 *   id = "latest_feeds",
 *   admin_label = @Translation("Latest Feeds Block"),
 *   category = @Translation("Latest Feeds Block"),
 * )
 */
class LatestFeeds extends BlockBase {

  /**
   * {@inheritdoc}
   */

  public function build() {

    global $base_url;
    $node_types = NodeType::loadMultiple();
  	$types = [];
    $type = '';
  	foreach ($node_types as $node_type) {
      $type .= '<li><a href="'.$base_url.'/latest/'.$node_type->id().'">'.$node_type->label().'</a></li>';
  	}
    $output = '<ul>'.$type.'</ul>';

    return array(
        '#type' => 'markup',
        '#markup' => $output,
        '#cache' => array(
             'max-age' => 0,
           ),
    );

  }

}