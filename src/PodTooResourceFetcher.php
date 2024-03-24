<?php

namespace Drupal\media_entity_podtoo;

use Drupal\media\OEmbed\ResourceFetcher;

/**
 * Extends the oEmbed resource fetcher for PodToo-specific settings.
 */
class PodTooResourceFetcher extends ResourceFetcher {

  /**
   * {@inheritdoc}
   */
  public function fetchResource($url) {
    // $url will be something like
    // https://embed.podtoo.com/api/oEmbed?url=https://embed.podtoo.com/Damon-Sharpe-presents-Brainjack-Radio/Brainjacked-Radio-#044
    $queries = [];
    // Check for a site-wide background color setting.
    $config = \Drupal::config('media_entity_podtoo.settings');
    $color = $config->get('color');
    if ($color !== '') {
      $queries['color'] = $color;
    }
    // Check for a site-wide display setting.
    $display = $config->get('display');
    if ($display === 'compact') {
      $queries['compact'] = 'true';
    }
    $parts = parse_url($url);
    parse_str($parts['query'], $query);
    // $url should be something like https://embed.podtoo.com/Damon-Sharpe-presents-Brainjack-Radio/Brainjacked-Radio-#044
    $source = $query['url'];
    if (!empty($parts['fragment'])) {
      $source .= '#' . $parts['fragment'];
    }
    $queries['url'] = $source;
    // $queries will be something like:
    // ['compact' => 'true'],
    // ['color' => 'FF0000'],
    // ['url' => 'https://embed.podtoo.com/Damon-Sharpe-presents-Brainjack-Radio/Brainjacked-Radio-#044'],
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
