<?php

namespace Drupal\drupal8_development\Controller;

use Drupal\Core\Controller\ControllerBase;

class SimpleCallback extends ControllerBase{

    public function sayHiAction($name)
    {

		$element = array(
		  '#markup' => $name,
		);
		return $element;
    }
}
?>