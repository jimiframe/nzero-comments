<?php

namespace Drupal\alps_cookbook\Plugin\metatag\Tag;

use Drupal\schema_metatag\Plugin\metatag\Tag\SchemaCountryBase;
use Drupal\schema_metatag\Plugin\metatag\Tag\SchemaNameBase;

/**
 * Schema.org CourseOffers items should extend this class.
 */
class SchemaCourseOffersBase extends SchemaNameBase {

  use SchemaCourseOffersTrait;

  /**
   * {@inheritdoc}
   */
  public function form(array $element = []): array {
    $value = $this->schemaMetatagManager()->unserialize($this->value());
    $input_values = [
      'title' => $this->label(),
      'description' => $this->description(),
      'value' => $value,
      '#required' => isset($element['#required']) ? $element['#required'] : FALSE,
      'visibility_selector' => $this->visibilitySelector(),
    ];

    $form = $this->courseOffersForm($input_values);

    if (empty($this->multiple())) {
      unset($form['pivot']);
    }

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public static function testValue() {
    $items = [];
    $keys = self::courseOffersFormKeys();
    foreach ($keys as $key) {
      switch ($key) {
        case '@type':
          $items[$key] = 'Offers';
          break;

        case 'eligibleRegion':
        case 'ineligibleRegion':
          $items[$key] = SchemaCountryBase::testValue();
          break;

        case 'price':
        case 'educationRequirements':
          $items[$key] = parent::testDefaultValue(2, ' ');
          break;

      }
    }
    return $items;
  }

  /**
   * {@inheritdoc}
   */
  public static function processedTestValue($items) {
    foreach ($items as $key => $value) {
      switch ($key) {
        case 'name':
        case 'educationRequirements':
          $items[$key] = static::processTestExplodeValue($items[$key]);
          break;
      }
    }
    return $items;
  }

}
