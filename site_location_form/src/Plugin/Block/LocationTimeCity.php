<?php

namespace Drupal\site_location_form\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\site_location_form\Services\CurrentTimeLocation;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Provides a 'Location, Tiem and City.
 *
 * @Block(
 *   id = "location_time_city_block",
 *   admin_label = @Translation("Location Time Block"),
 * )
 */
class LocationTimeCity extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Constructs a Drupalist object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Session\AccountInterface $currentUser
   *   The current_user.
   * @param \Drupal\site_location_form\Services\CurrentTimeLocation $currentTimeLocation
   *   The current_user.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The current_user.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    protected AccountInterface $currentUser,
    protected CurrentTimeLocation $currentTimeLocation,
    protected ConfigFactoryInterface $configFactory,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('current_user'),
      $container->get('site_location_and_time.service'),
      $container->get('config.factory'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $current_time[] = $this->currentTimeLocation->getCurrentTime();
    $config = $this->configFactory->get("site_location_time.settings");
    $city = $config->get('city');
    $location = $config->get('country');
    return [
      '#theme' => 'site_location_time',
      '#current_time' => $current_time[0]['format_time'],
      '#current_date' => $current_time[0]['format_date'],
      '#city' => $city,
      '#location' => $location,
      '#cache' => [
        'max-age' => 60,
        'tags' => ['custom_time_dependency'],
        'contexts' => ['url.path'],
      ],
    ];
  }

}
