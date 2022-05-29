<?php

namespace Drupal\alex\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;

/**
 * @ContentEntityType(
 *   id = "alex",
 *   label = @Translation("alex"),
 *   base_table = "alex",
 *   entity_keys = {
 *     "id" = "id",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *   "canonical" = "/alex/{alex}",
 *   "delete" = "/alex/{alex}/delete",
 *   "edit" = "/alex/{alex}/edit",
 *   },
 *   handlers = {
 *    "access" = "Drupal\Core\Entity\EntityAccessControlHandler",
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\alex\Controller\AlexController",
 *     "views_data" = "Drupal\Core\Views\EntityViewsData",
 *   "form" = {
 *       "default" = "Drupal\alex\Form\AlexForm",
 *       "delete" = "Drupal\alex\Form\AlexDeleteForm",
 *     },
 *   "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\DefaultHtmlRouteProvider",
 *     },
 *   },
 * )
 */
class AlexEntity extends ContentEntityBase {

  /**
   * Make fields for my entity.
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {
    $fields = parent::baseFieldDefinitions($entity_type);
    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language code'))
      ->setDescription(t('The language code of ContentEntityExample entity.'));
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('id'))
      ->setDescription(t('id'))
      ->setReadOnly(TRUE);
    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('uuid'))
      ->setReadOnly(TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setSetting('max_length', 100)
      ->setRequired(TRUE)
      ->setDefaultValue(NULL)
      ->setPropertyConstraints('value', [
        'Length' => [
          'min' => 2,
          'max' => 100,
          'minMessage' => 'Please enter a longer name.',
        ],
      ])
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => 10,
        'settings' => [
          'placeholder' => '2 - 100 symbols',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['email'] = BaseFieldDefinition::create('email')
      ->setLabel(t('Email'))
      ->setDefaultValue(NULL)
      ->setRequired(TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('view', TRUE)
      ->setDisplayOptions('form', [
        'weight' => 10,
        'settings' => [
          'placeholder' => 'email@example.com',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE);

    $fields['phone'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Phone number'))
      ->setSetting('max_length', 20)
      ->setRequired(TRUE)
      ->setPropertyConstraints('value', [
        'Regex' => [
          'pattern' => '/^[0-9]{12}$/',
          'message' => t('Format: 380XXXXXXXXX'),
        ],
      ]
      )
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => 10,
      ])
      ->setDisplayOptions('form', [
        'weight' => 10,
        'settings' => [
          'placeholder' => ' 380XXXXXXXXX',
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['review'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Review'))
      ->setRequired(TRUE)
      ->setDefaultValue(NULL)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'weight' => 10,
      ])
      ->setDisplayOptions('form', [
        'weight' => 10,
        'settings' => [
          'placeholder' => 'Your feedback',
        ],
      ]);
    $fields['avatar'] = BaseFieldDefinition::create('image')
      ->setLabel(t('User photo'))
      ->setSettings([
        'file_extensions' => 'png jpg jpeg',
        'file_directory' => 'public://images/',
        'max_filesize' => 2097152,
        'alt_field' => FALSE,
      ])
      ->setDefaultValue(NULL)
      ->setDisplayOptions('form', [
        'type' => 'image',
        'label' => 'hidden',
      ])
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'image',
      ]);
    $fields['image'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Review photo'))
      ->setSettings([
        'file_extensions' => 'png jpg jpeg',
        'file_directory' => 'public://images/',
        'max_filesize' => 5242880,
        'alt_field' => FALSE,
      ])
      ->setDisplayOptions('form', [
        'label' => 'hidden',
        'type' => 'image',
      ])
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'image',
      ]);
    $fields['date'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Date'))
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'datetime_custom',
        'settings' => [
          'data_format' => 'm/d/Y H:i:s',
        ],
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE);
    return $fields;
  }

}
