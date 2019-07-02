<?php

namespace Drupal\latest_feeds\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;

class LatestFeedsSubscriber implements EventSubscriberInterface {


	public function customentityUpdate($event) {
	   //dump($event);
	   //die();
  }

  public static function getSubscribedEvents(){
  	$events[KernelEvents::REQUEST][] = ['customentityUpdate'];
    return $events;
  }

}