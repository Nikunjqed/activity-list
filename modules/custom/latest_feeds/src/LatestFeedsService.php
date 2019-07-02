<?php

namespace Drupal\latest_feeds;

use Drupal\node\Entity\Node;

/**
 * Simple example of the current user
 */
class LatestFeedsService
{
   /**
   * Current User.
   *
   * @var Drupal\Core\Session\AccountProxy
   */
	private $FeedType;

	public function getFeeds($FeedType){

		$query = \Drupal::entityQuery('node')
		    ->condition('status', 1) //published or not
		    ->condition('type', $FeedType) //content type
		    ->sort('changed' , 'DESC')
		    ->range(0, 5);
	    $Feeds = $query->execute();
	    $FeedsDetail = [];
	    foreach ($Feeds as $Feedkey => $FeedValue) {
	      $Node = Node::load($FeedValue);
	      $FeedsDetail[$FeedValue]['title'] = $Node->label();
	      $FeedsDetail[$FeedValue]['author'] = $Node->getOwner()->getDisplayName();
	    }
		return $FeedsDetail;
	} 
}