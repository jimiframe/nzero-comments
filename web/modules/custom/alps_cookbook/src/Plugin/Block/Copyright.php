<?php

namespace Drupal\alps_cookbook\Plugin\Block;

use Drupal\Core\Annotation\Translation;
use Drupal\Core\Block\BlockBase;

/**
 * @Block(
 *   id = "copyright_block",
 *   admin_label = @Translation("Copyright"),
 *   category= @Translation("Custom"),
 * )
 */
class Copyright extends BLockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $date = new \DateTime();
    return [
      '#markup' => t('Copyright @year&copy; cd pro[]', ['@year' => $date->format('Y')])
    ];
  }
}
