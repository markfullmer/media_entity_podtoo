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
    'standard' => 'Standard',
    'compact' => 'Compact',
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
      '#description' => $this->t('Enter a color code in <a href="https://en.wikipedia.org/wiki/Web_colors" target="_blank">hex format</a> (e.g., "FF0000".). Leave blank to use the default PodToo player color.'),
      '#type' => 'textfield',
      '#size' => 6,
      '#default_value' => $color ?? '',
    ];
    $form['user'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Personalisation data sent to PodToo'),
    ];
    $username = $settings->get('username');
    $email = $settings->get('email');
    $uid = $settings->get('uid');
    $form['user']['username'] = [
      '#type' => 'checkbox',
      '#title' => 'When media is requested, send Drupal user <code>username</code> as parameter to PodToo endpoint.',
      '#default_value' => $username ?? FALSE,
    ];
    $form['user']['email'] = [
      '#type' => 'checkbox',
      '#title' => 'When media is requested, send Drupal user <code>email</code> as parameter to PodToo endpoint.',
      '#default_value' => $email ?? FALSE,
    ];
    $form['user']['uid'] = [
      '#type' => 'checkbox',
      '#title' => 'When media is requested, send Drupal user <code>uid</code> as parameter to PodToo endpoint.',
      '#default_value' => $uid ?? FALSE,
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $color = $form_state->getValue('color');
    if (!empty($color) && !preg_match('/^[a-f0-9]{6}$/i', $color)) {
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
    $config->set('uid', $form_state->getValue('uid'))->save();
    $config->set('username', $form_state->getValue('username'))->save();
    $config->set('email', $form_state->getValue('email'))->save();
    drupal_flush_all_caches();
    parent::submitForm($form, $form_state);
  }

}
