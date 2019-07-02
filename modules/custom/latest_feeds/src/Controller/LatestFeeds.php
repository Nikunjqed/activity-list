<?php

namespace Drupal\latest_feeds\Controller;

use Drupal\latest_feeds\LatestFeedsService;
use Symfony\Component\DependencyInjection\ContainerInterface;

use Drupal\Core\Controller\ControllerBase;

class LatestFeeds extends ControllerBase{
	/**
      * @var \Drupal\latest_feeds\LatestFeeds
      */
     protected $feeds;
     /**
      * LatestFeeds constructor.
      *
      * @param \Drupal\latest_feeds\LatestFeeds $salutation
      */
     public function __construct(LatestFeedsService $feeds) {
       $this->feeds = $feeds;
	}
     /**
      * {@inheritdoc}
      */
      public static function create(ContainerInterface $container) {
       return new static($container->get('latest_feeds.feed'));
	}

    public function getFeed($type)
    {

    	$FeedsData = $this->feeds->getFeeds($type);
    	foreach ($FeedsData as $FeedsDataKey => $FeedsDataValue) {
    		$FeedDetail .= '<li><b>Title:</b> '.$FeedsDataValue['title'].'<b> Author:</b></li> '. $FeedsDataValue['author'].'</li>';
    	}
    	$output = '<ul>'.$FeedDetail.'</ul>';
		$element = array(
		  '#markup' => $output,
		  '#cache' => array(
          'tags' => ['node_list'],
        ),
		);

		return $element;
    }
}
?>