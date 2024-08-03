<?php

namespace Drupal\alps_comments\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

  /**
   * Provides a 'Comments Block' block.
   *
   * @Block(
   *   id = "comments_block",
   *   admin_label = @Translation("Alps Trip Comments Block"),
   *   category = @Translation("Alps Trip Comments")
   * )
   */
class CommentsBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $database;

  /**
   * Constructs a new UserCommentsBlock instance.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin ID for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, Connection $database) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->database = $database;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $user = \Drupal::currentUser();
    $rangeComments = 5;

    $total_comments = $this->getTotalComments($user->id());
    $last_comments = $this->getLastComments($user->id(), $rangeComments);
    $total_words = $this->getTotalWords($user->id());

    return [
      '#theme' => 'alps_comments_block',
      '#total_comments' => $total_comments,
      '#last_comments' => $last_comments,
      '#total_words' => $total_words,
    ];
  }


  /**
   * Get the total number of comments by a user.
   */
  protected function getTotalComments($uid) {
    $query = $this->database->select('comment_field_data', 'cfd')
      ->condition('uid', $uid)
      ->countQuery();
    return $query->execute()->fetchField();
  }

  /**
   * Get the last 5 comments by a user.
   */
  protected function getLastComments($uid, $rangeComments) {
    $query = $this->database->select('comment_field_data', 'cfd')
      ->fields('cfd', ['cid', 'entity_id', 'subject', 'created'])
      ->condition('uid', $uid)
      ->orderBy('created', 'DESC')
      ->range(0, $rangeComments);
    $results = $query->execute()->fetchAll();

    $comments = [];
    foreach ($results as $result) {
      $node = \Drupal\node\Entity\Node::load($result->entity_id);
      $comments[] = [
        'subject' => $result->subject,
        'node_title' => $node ? $node->getTitle() : '',
        'created' => date('Y-m-d H:i:s', $result->created),
      ];
    }

    return $comments;
  }

  /**
   * Get the total number of words in comments by a user.
   */
  protected function getTotalWords($uid) {
    $query = $this->database->select('comment_field_data', 'cfd')
      ->fields('cfd', ['cid'])
      ->condition('uid', $uid);
    $commentsCid = $query->execute()->fetchAll();

    $comments = [];

    if (!empty($commentsCid)) {
      foreach ($commentsCid as $cid) {
        $query = $this->database->select('comment__comment_body', 'ccb')
          ->fields('ccb', ['comment_body_value'])
          ->condition('entity_id', $cid->cid);
        $commentBody = $query->execute()->fetchAllAssoc('comment_body_value');
        $comments = array_merge($comments, array_keys($commentBody));
      }

    }

    $total_words = 0;
    foreach ($comments as $comment) {
      $total_words += str_word_count(strip_tags($comment));
    }

    return $total_words;
  }

  /**
   * {@inheritdoc}
   */
  protected function blockAccess(AccountInterface $account) {
    return AccessResult::allowedIf($account->isAuthenticated());
  }

}
