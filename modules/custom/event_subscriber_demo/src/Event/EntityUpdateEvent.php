<?php

namespace Drupal\event_subscriber_demo\Event;

use Symfony\Component\EventDispatcher\Event;
use Drupal\Core\Entity\EntityInterface;

/**
 * Wraps a node insertion demo event for event listeners.
 */
class EntityUpdateEvent extends Event {

  const ENTITY_UPDATE = 'event_subscriber_demo.entity.update';
}
