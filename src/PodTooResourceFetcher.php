<?php

namespace Drupal\media_entity_podtoo;

use Drupal\media\OEmbed\ResourceFetcher;

/**
 * Extends the oEmbed resource fetcher with SoundCloud-specific workarounds.
 *
 * The service must be extended because there is currently no way to inject a
 * specialized XML resource parser to handle SoundCloud-specific quirks.
 */
class PodTooResourceFetcher extends ResourceFetcher {

  /**
   * {@inheritdoc}
   */
  public function fetchResource($url) {
    \Drupal::logger('media_entity_podtoo')->notice($url);
  }

}
