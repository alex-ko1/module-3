<?php

namespace Drupal\alex\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Controller\ControllerBase;

/**
 * Provides route responses for the alex module.
 */
class AlexController extends ControllerBase {

  /**
   * Drupal services.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityManager;
  /**
   * Drupal services.
   *
   * @var \Drupal\Core\Entity\EntityFormBuilder
   */
  protected $formBuild;

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container): AlexController {
    $instance = parent::create($container);
    $instance->entityManager = $container->get('entity_type.manager');
    $instance->formBuild = $container->get('entity.form_builder');
    return $instance;
  }

  /**
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */

  /**
   * Method that create output of module.
   */
  public function build(): array {
    $entity = $this->entityManager
      ->getStorage('alex')
      ->create([
        'entity_type' => 'node',
        'entity' => 'alex',
      ]);
    $storage = $this->entityManager->getStorage('alex');
    $form = $this->formBuild->getForm($entity, 'default');
    $query = $storage->getQuery()
      ->sort('date', 'DESC')
      ->pager(5);
    $pager = [
      '#type' => 'pager',
    ];
    $reviews = $query->execute();
    $result = $storage->loadMultiple($reviews);
    $view_builder = $this->entityManager->getViewBuilder('alex');
    $full_output = $view_builder->viewMultiple($result);
    return [
      '#theme' => 'guest-page',
      '#form' => $form,
      '#pager' => $pager,
      '#reviews' => $full_output,
    ];
  }

}
