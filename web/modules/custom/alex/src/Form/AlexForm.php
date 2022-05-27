<?php

namespace Drupal\alex\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\MessageCommand;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Language\Language;

/**
 * Form for the alex entity.
 */
class AlexForm extends ContentEntityForm {

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    $form['name']['widget'][0]['value']['#ajax'] = [
      'callback' => '::validateName',
      'event' => 'change',
      'disable-refocus' => TRUE,
    ];
    $form['email']['widget'][0]['value']['#ajax'] = [
      'callback' => '::validateEmail',
      'event' => 'change',
      'disable-refocus' => TRUE,
    ];
    $form['phone']['widget'][0]['value']['#ajax'] = [
      'callback' => '::validatePhone',
      'event' => 'change',
      'disable-refocus' => TRUE,
    ];

    return $form;
  }

  /**
   * @throws \Drupal\Core\Entity\EntityStorageException
   */

  /**
   * {@inheritDoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->getEntity();
    $entity->save();
    $form_state->setRedirect('alex');
  }

  /**
   * Validate Name.
   */
  public function validateName(array &$form, FormStateInterface $form_state): object {
    $response = new AjaxResponse();
    $name = $form_state->getValue('name')[0]['value'];
    $pattern = '/^[aA-zZ]{2,100}$/';
    if (!preg_match($pattern, $name)) {
      $response->addCommand(new MessageCommand('Write name in allowed format!',  NULL, ['type'=>'error']));
    }
    else {
      $response->addCommand(new MessageCommand('Correct name!', '#for-message'));
    }
    if ($form_state->hasAnyErrors()) {
      foreach ($form_state->getErrors() as $errors_array) {
        $response->addCommand(new MessageCommand($errors_array, NULL, ['type'=>'error']));
      }
    }
    return $response;
  }

  /**
   * Validate email.
   */
  public function validateEmail(array &$form, FormStateInterface $form_state): object {
    $response = new AjaxResponse();
    $email = $form_state->getValue('email')[0]['value'];
    $pattern = '/^\S+@\S+\.\S+$/';
    if (!preg_match($pattern, $email)) {
      $response->addCommand(new MessageCommand('Invalid email!', NULL, ['type'=>'error']));
    }
    else {
      $response->addCommand(new MessageCommand('Correct email!',  ));
    }
    return $response;
  }

  /**
   * Validate phone number.
   */
  public function validatePhone(array &$form, FormStateInterface $form_state): object {
    $response = new AjaxResponse();
    $phone = $form_state->getValue('phone')[0]['value'];
    $pattern = '/^[0-9]{12}$/';
    if (!preg_match($pattern, $phone)) {
      $response->addCommand(new MessageCommand('Invalid phone number!', '#for-message', NULL, ['type'=>'error']));
    }
    else {
      $response->addCommand(new MessageCommand('Correct phone number!', '#for-message', [], FALSE));
    }
    return $response;
  }

}
