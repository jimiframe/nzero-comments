<?php

namespace Drupal\alps_comments;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Session\AccountInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Log\LoggerInterface;

/**
 * Class CommentsClient.
 */
class CommentsClient implements CommentsClientInterface {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $account;

  /**
   * Constructs a new WeatherClient object.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *    The current user.
   * @param \GuzzleHttp\ClientInterface $httpClient
   *   The HTTP client.
   * @param \Psr\Log\LoggerInterface $loggerFactory
   *   The logger factory.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $configFactory
   *   The config factory.
   */
  public function __construct(
    AccountInterface $account,
    private readonly ClientInterface $httpClient,
    private readonly LoggerInterface $loggerFactory,
    private readonly ConfigFactoryInterface $configFactory,
  ) {
    $this->account = $account;
  }

  /**
   * {@inheritdoc}
   */
  public function getCommentsData(): array {

    if ($this->account->isAuthenticated()) {
      // Code to fetch comments for authenticated user.
    }

  }

  /**
   * {@inheritdoc}
   */
  public function getCommentsDetail(string $city, string $date): array {
    $forecast = $this->getForecastData($city);

    $detail = array_filter($forecast['list'], function ($item) use ($date) {
      return $item['dt_txt'] == $date;
    });

    return array_shift($detail);
  }

}
