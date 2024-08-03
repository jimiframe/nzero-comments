<?php

namespace Drupal\alps_comments;

/**
 * Interface CommentsClientInterface.
 */
interface CommentsClientInterface {

  /**
   * Get Comments data.
   *
   * @param string $user
   *   The city name.
   *
   * @return array
   *   The weather data.
   */
  public function getCommentsData(): array;

  /**
   * @param string $city
   * @param string $date
   *
   * @return array
   */
  public function getCommentsDetail($node): array;

}
