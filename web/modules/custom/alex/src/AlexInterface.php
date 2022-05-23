<?php

namespace Drupal\alex;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\user\EntityOwnerInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining an alex entity type.
 */
interface AlexInterface extends ContentEntityInterface, EntityOwnerInterface, EntityChangedInterface {

  /**
   * Gets the alex title.
   *
   * @return string
   *   Title of the alex.
   */
  public function getTitle();

  /**
   * Sets the alex title.
   *
   * @param string $title
   *   The alex title.
   *
   * @return \Drupal\alex\AlexInterface
   *   The called alex entity.
   */
  public function setTitle($title);

  /**
   * Gets the alex creation timestamp.
   *
   * @return int
   *   Creation timestamp of the alex.
   */
  public function getCreatedTime();

  /**
   * Sets the alex creation timestamp.
   *
   * @param int $timestamp
   *   The alex creation timestamp.
   *
   * @return \Drupal\alex\AlexInterface
   *   The called alex entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the alex status.
   *
   * @return bool
   *   TRUE if the alex is enabled, FALSE otherwise.
   */
  public function isEnabled();

  /**
   * Sets the alex status.
   *
   * @param bool $status
   *   TRUE to enable this alex, FALSE to disable.
   *
   * @return \Drupal\alex\AlexInterface
   *   The called alex entity.
   */
  public function setStatus($status);

}
