<?php

namespace Drupal\site_location_form\Form;

use Drupal\Core\Cache\CacheTagsInvalidatorInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a Admin form for Timezone, City and Country.
 */
class SiteLocationTimeAdminForm extends ConfigFormBase {
  /**
   * The Cache Tags Invalidator Interface.
   *
   * @var \Drupal\Core\Cache\CacheTagsInvalidatorInterface
   */
  protected $cacheTagsInvalidator;

  /**
   * {@inheritdoc}
   */
  public function __construct(CacheTagsInvalidatorInterface $cacheTagsInvalidator) {
    $this->cacheTagsInvalidator = $cacheTagsInvalidator;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
        $container->get('cache_tags.invalidator')
      );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['site_location_time.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'site_location_time_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config("site_location_time.settings");
    $form['country'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Country'),
      '#default_value' => $config->get('country'),
    ];

    $form['city'] = [
      '#type' => 'textfield',
      '#title' => $this->t('City'),
      '#default_value' => $config->get('city'),
    ];
    $timeZone = [
      '' => '-Select-',
      'america/chicago' => 'America/Chicago',
      'america/new_york' => 'America/New_York',
      'asia/tokyo' => 'Asia/Tokyo',
      'asia/dubai' => 'Asia/Dubai',
      'asia/kolkata' => 'Asia/Kolkata',
      'europe/amsterdam' => 'Europe/Amsterdam',
      'europe/oslo' => 'Europe/Oslo',
      'europe/london' => 'Europe/London',
    ];

    $form['timezone'] = [
      '#type' => 'select',
      '#title' => $this->t('Timezone'),
      '#default_value' => $config->get('timezone'),
      '#options' => $timeZone,
    ];

    // Add other form elements as needed.
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('site_location_time.settings');
    $this->cacheTagsInvalidator->invalidateTags(['custom_time_dependency']);
    $config->set('country', $form_state->getValue('country'));
    $config->set('city', $form_state->getValue('city'));
    $config->set('timezone', $form_state->getValue('timezone'));
    $config->save();
    parent::submitForm($form, $form_state);
  }

}
