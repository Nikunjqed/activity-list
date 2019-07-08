<?php

namespace Drupal\event_subscriber_demo\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Drupal\event_subscriber_demo\Event\EntityUpdateEvent;

/**
 * Logs the creation of a new node.
 */
class EntityUpdateSubscriber implements EventSubscriberInterface {

  /**
   * Log the creation of a new node.
   *
   * @param \Drupal\event_subscriber_demo\Event\EntityUpdateEvent $event
   */
  public function onEntityUpdate(EntityUpdateEvent $event) {
    $request_time = \Drupal::time()->getCurrentTime();
    $request_time = date(DATE_RFC822, $request_time);
    \Drupal::state()->set('entity_update',$request_time);
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[EntityUpdateEvent::ENTITY_UPDATE][] = ['onEntityUpdate'];
    return $events;
  }
}
