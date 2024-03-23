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
    $config = \Drupal::config('media_entity_podtoo.settings');
    $color = $config->get('color');
    $queries = [];
    if ($color !== '') {
      $queries['color'] = $color;
    }
    $display = $config->get('display');
    if ($display === 'compact') {
      $queries['compact'] = 'true';
    }
    $parts = parse_url($url);
    parse_str($parts['query'], $query);
    $queries['url'] = $query['url'] . '#' . $parts['fragment'];
    $compiled_query = http_build_query($queries);
    $full = [
      $parts['scheme'],
      '://',
      $parts['host'],
      $parts['path'],
      '?',
      $compiled_query,
    ];
    $new_url = implode("", $full);
    $result = parent::fetchResource($new_url);
    return $result;
  }

}
