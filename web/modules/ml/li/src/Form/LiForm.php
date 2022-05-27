<?php

namespace Drupal\li\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\RedirectCommand;

/**
 * Form controller for the li entity edit forms.
 */
class LiForm extends ContentEntityForm {

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
      $this->messenger()->addStatus($this->t('New li %label has been created.', $message_arguments));
      $this->logger('li')->notice('Created new li %label', $logger_arguments);
    }
    else {
      $this->messenger()->addStatus($this->t('The li %label has been updated.', $message_arguments));
      $this->logger('li')->notice('Updated new li %label.', $logger_arguments);
    }

    $form_state->setRedirect('entity.li.canonical', ['li' => $entity->id()]);
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {
    $form = parent::buildForm($form, $form_state);
    $form['#attached']['library'][] = 'li/inputmask';
    $form['#prefix'] = '<div id="li-form"';
    $form['#suffix'] = '</div>';
    $form['actions']['submit']['#ajax'] = [
      'callback' => '::submitAjax',
      'wrapper' => 'li-form',
      'progress' => 'none',
    ];
    return $form;
  }

  /**
   * AJAX validation and confirmation of the form.
   *
   * @param array $form
   *   An associative array containing the structure of the form.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The current state of the form.
   *
   * @return array|\Drupal\core\Ajax\AjaxResponse
   *   Return form or Ajax response.
   */
  public function submitAjax(array $form, FormStateInterface $form_state) {
    if ($form_state->hasAnyErrors()) {
      return $form;
    }
    $li = new AjaxResponse();
    $li->addCommand(new RedirectCommand('/li'));
    return $li;
  }

}
