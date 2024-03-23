<?php

namespace Drupal\media_entity_podtoo\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configuration for Media Entity PodToo.
 */
class ConfigurationForm extends ConfigFormBase {

  /**
   * @var display
   */
  public static $display = [
    'compact' => 'Compact',
    'standard' => 'Standard',
  ];

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'media_entity_podtoo_config';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $settings = $this->config('media_entity_podtoo.settings');
    $display = $settings->get('display');
    $form['display'] = [
      '#title' => $this->t('Display format'),
      '#type' => 'radios',
      '#options' => self::$display,
      '#default_value' => $display ?? 'standard',
    ];
    $color = $settings->get('color');
    $form['color'] = [
      '#title' => $this->t('Player background color'),
      '#description' => $this->t('Leave blank to use the default PodToo player or enter a color code in <a href="https://en.wikipedia.org/wiki/Web_colors" target="_blank">hex format</a> (e.g., "FF0000".'),
      '#type' => 'textfield',
      '#size' => 6,
      '#default_value' => $color ?? '',
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $color = $form_state->getValue('color');
    if (!preg_match('/^[a-f0-9]{6}$/i', $color)) {
      $form_state->setErrorByName('color', $this->t('Enter a valid color code in hex format or leave the field blank.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->configFactory->getEditable('media_entity_podtoo.settings');
    $config->set('display', $form_state->getValue('display'))->save();
    $config->set('color', $form_state->getValue('color'))->save();
    drupal_flush_all_caches();
    parent::submitForm($form, $form_state);
  }

}
