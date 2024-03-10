<?php

namespace Drupal\media_entity_podtoo;

use Drupal\media\OEmbed\Provider;
use Drupal\media\OEmbed\ProviderRepositoryInterface;

/**
 * A provider repository that always reads from a specific fixture file.
 */
final class ProviderRepository implements ProviderRepositoryInterface {

  /**
   * {@inheritdoc}
   */
  public function getAll() {
    $list = __DIR__ . '/../fixtures/providers.json';
    $list = file_get_contents($list);
    $list = json_decode($list, TRUE, 512, JSON_THROW_ON_ERROR);

    $all = [];
    foreach ($list as $p) {
      $name = $p['provider_name'];
      $all[$name] = new Provider($name, $p['provider_url'], $p['endpoints']);
    }
    return $all;
  }

  /**
   * {@inheritdoc}
   */
  public function get($provider_name) {
    $all = $this->getAll();
    if (array_key_exists($provider_name, $all)) {
      return $all[$provider_name];
    }
    throw new \InvalidArgumentException("No such provider: '$provider_name'");
  }

}
