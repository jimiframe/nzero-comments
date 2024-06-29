<?php

namespace Drupal\alps_cookbook\Plugin\metatag\Tag;



trait SchemaCourseOffersTrait {

  /**
   * Return the SchemaMetatagManager.
   *
   * @return \Drupal\schema_metatag\SchemaMetatagManager
   *   The Schema Metatag Manager service.
   */
  abstract protected function schemaMetatagManager();

  /**
   * Form keys.
   */
  public static function courseOffersFormKeys() {
    return [
      '@type',
      'category',
      'priceCurrency',
      'price',
    ];
  }

  /**
   * The form element.
   */
  public function courseOffersForm($input_values) {
    $input_values += $this->schemaMetatagManager()->defaultInputValues();
    $value = $input_values['value'];

    $form['#type'] = 'fieldset';
    $form['#title'] = $input_values['title'];
    $form['#description'] = $input_values['description'];
    $form['#tree'] = TRUE;

    $form['@type'] = [
      '#type' => 'select',
      '#title' => $this->t('@type'),
      '#options' => [
        'Offer' => $this->t('Offer'),
      ],
      '#default_value' => !empty($value['@type']) ? $value['@type'] : '',
      '#weight' => -10,
    ];

    $form['category'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Category'),
      '#default_value' => !empty($value['category']) ? $value['category'] : 'Paid',
      '#maxlength' => 255,
      '#required' => $input_values['#required'],
      '#description' => $this->t('The offer category.'),
    ];

    $form['priceCurrency'] = [
      '#type' => 'textfield',
      '#title' => $this->t('priceCurrency'),
      '#default_value' => !empty($value['priceCurrency']) ? $value['priceCurrency'] : 'EUR',
      '#maxlength' => 255,
      '#required' => $input_values['#required'],
      '#description' => $this->t('The offer priceCurrency.'),
    ];

    $form['price'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Price'),
      '#default_value' => $value['price'] ?? '',
      '#maxlength' => 255,
      '#required' => $input_values['#required'],
      '#description' => $this->t('The offer price.'),
    ];

    return $form;
  }

}
