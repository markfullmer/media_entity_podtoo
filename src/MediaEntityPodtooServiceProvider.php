<?php

namespace Drupal\media_entity_podtoo;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * Overrides the media.oembed.resource_fetcher service.
 */
class MediaEntityPodtooServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    $container->getDefinition('media.oembed.resource_fetcher')
      ->setClass(PodTooResourceFetcher::class);
  }

}
