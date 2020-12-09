<?php
/**
 * @file
 * @author BHP
 * Contains '\Drupal\engine_settings\Controller\eng_controller
 * Please place this file under your example(module_root_folder)/src/Controller/
 */
namespace Drupal\engine_settings\Controller;
/**
 * Provides route responses for the Example module.
 */
class eng_controller {
  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function show_engine_settings() {
    $element = array(
      '#theme' => 'my-template',
      '#data' => 'Hello world!',
    );
    return $element;
  }
}
?>