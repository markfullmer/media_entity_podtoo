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
    $all = [];
    $endpoints = [];
    $endpoints[] = [
      'schemes' => [
        'https://embed.podtoo.com/*',
        'https://podcasts.podtoo.com/*',
      ],
      'url' => 'https://embed.podtoo.com/api/oEmbed',
      'discovery' => FALSE,
    ];
    $all['Podtoo'] = new Provider('Podtoo', 'https://podtoo.com', $endpoints);
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
