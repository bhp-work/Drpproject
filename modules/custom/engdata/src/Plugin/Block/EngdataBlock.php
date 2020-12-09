<?php

namespace Drupal\engdata\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'engdataBlock' block.
 *
 * @Block(
 *  id = "engdata_block",
 *  admin_label = @Translation("engdata block"),
 * )
 */
class engdataBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    ////$build = [];
    //$build['engdata_block']['#markup'] = 'Implement engdataBlock.';

    $form = \Drupal::formBuilder()->getForm('Drupal\engdata\Form\engdataForm');

    return $form;
  }

}
