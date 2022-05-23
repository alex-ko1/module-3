<?php

namespace Drupal\alex\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for the alex entity edit forms.
 */
class AlexForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {

    $entity = $this->getEntity();
    $result = $entity->save();
    $link = $entity->toLink($this->t('View'))->toRenderable();

    $message_arguments = ['%label' => $this->entity->label()];
    $logger_arguments = $message_arguments + ['link' => render($link)];

    if ($result == SAVED_NEW) {
      $this->messenger()->addStatus($this->t('New alex %label has been created.', $message_arguments));
      $this->logger('alex')->notice('Created new alex %label', $logger_arguments);
    }
    else {
      $this->messenger()->addStatus($this->t('The alex %label has been updated.', $message_arguments));
      $this->logger('alex')->notice('Updated new alex %label.', $logger_arguments);
    }

    $form_state->setRedirect('entity.alex.canonical', ['alex' => $entity->id()]);
  }

}
