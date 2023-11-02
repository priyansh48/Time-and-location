<?php

namespace Drupal\site_location_form\Services;

use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Provide Custom Service return Current Time.
 */
class CurrentTimeLocation {
  /**
   * The Config Factory Interface.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * Constructs a new EntityActionDeriverBase object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The Config Factory Interface.
   */
  public function __construct(ConfigFactoryInterface $configFactory) {
    $this->configFactory = $configFactory;
  }

  /**
   * {@inheritdoc}
   */
  public function getCurrentTime() {

    $timezone = $this->configFactory->get('site_location_time.settings')->get('timezone');
    if ($timezone) {
      // Create a DateTime object in the specified timezone.
      $datetime = new \DateTime('now', new \DateTimeZone($timezone));
      // Format the time as 'h:i A' (e.g., 11:15 AM)
      $time_format = $datetime->format('h:i A');
      // Format the date as 'l, jS F Y' (e.g., Monday, 1st November 2023)
      $date_format = $datetime->format('l, jS F Y');
      return [
        'format_time' => $time_format,
        'format_date' => $date_format,
      ];
    }
  }

}
