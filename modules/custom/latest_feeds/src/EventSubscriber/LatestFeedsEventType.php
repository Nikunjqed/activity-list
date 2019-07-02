<?php


use Drupal\Core\Entity\EntityTypeEventSubscriberTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\EntityTypeListenerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LatestFeedsEventType implements EntityTypeListenerInterface, EventSubscriberInterface{

	use EntityTypeEventSubscriberTrait;

	public function __construct(EntityManagerInterface $entity_manager) {
	    $this->entityManager = $entity_manager;
	}

	public function onUpdate(EntityTypeEvent $event)  {
	      drupal_set_message('Entity inserted custom message111');
	}

	/**
	 * {@inheritdoc}
	 */
	public static function getSubscribedEvents() {
	    return static::getEntityTypeEvents();
	}
}