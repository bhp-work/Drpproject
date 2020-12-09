<?php

namespace Drupal\engdata\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class engdataController.
 *
 * @package Drupal\engdata\Controller
 */
class engdataController extends ControllerBase {

  /**
   * Display.
   *
   * @return string
   *   Return Hello string.
   */
  public function display() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('This page contain all inforamtion about engine configuration')
    ];
  }

}
