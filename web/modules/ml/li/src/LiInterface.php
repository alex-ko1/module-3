<?php

namespace Drupal\li;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a li entity type.
 */
interface LiInterface extends ContentEntityInterface,
  EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the li title.
   *
   * @return string
   *   Title of the li.
   */
  public function getTitle();

  /**
   * Sets the li title.
   *
   * @param string $title
   *   The li title.
   *
   * @return \Drupal\li\LiInterface
   *   The called li entity.
   */
  public function setTitle($title);

  /**
   * Gets the li creation timestamp.
   *
   * @return int
   *   Creation timestamp of the li.
   */
  public function getCreatedTime();

  /**
   * Sets the li creation timestamp.
   *
   * @param int $timestamp
   *   The li creation timestamp.
   *
   * @return \Drupal\li\LiInterface
   *   The called li entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the li status.
   *
   * @return bool
   *   TRUE if the li is enabled, FALSE otherwise.
   */
  public function isEnabled();

  /**
   * Sets the li status.
   *
   * @param bool $status
   *   TRUE to enable this li, FALSE to disable.
   *
   * @return \Drupal\li\LiInterface
   *   The called li entity.
   */
  public function setStatus($status);

}
