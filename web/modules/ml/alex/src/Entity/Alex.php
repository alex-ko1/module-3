<?php

namespace Drupal\alex\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\alex\AlexInterface;
use Drupal\user\UserInterface;

/**
 * Defines the alex entity class.
 *
 * @ContentEntityType(
 *   id = "alex",
 *   label = @Translation("alex"),
 *   label_collection = @Translation("alexs"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\alex\AlexListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\alex\Form\AlexForm",
 *       "edit" = "Drupal\alex\Form\AlexForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "alex",
 *   admin_permission = "administer alex",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "title",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "add-form" = "/admin/content/alex/add",
 *     "canonical" = "/alex/{alex}",
 *     "edit-form" = "/admin/content/alex/{alex}/edit",
 *     "delete-form" = "/admin/content/alex/{alex}/delete",
 *     "collection" = "/admin/content/alex"
 *   },
 *   field_ui_base_route = "entity.alex.settings"
 * )
 */
class Alex extends ContentEntityBase implements ContentEntityInterface {

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Advertiser entity.'))
      ->setReadOnly(TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Advertiser entity.'))
      ->setReadOnly(TRUE);

    //Field, used for input user name.
    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the user who adds the review'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 100)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
        'settings' => [
          'placeholder' => 'Enter name',
        ],
      ])
      ->setPropertyConstraints('value', [
        'Length' => [
          'min' => 2,
          'max' => 100,
          'minMessage' => t('Minimum name length 2 characters.'),
          'maxMessage' => t('Maximum name length 100 characters.'),
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('view', TRUE);

    //Field, used for input user email.
    $fields['email'] = BaseFieldDefinition::create('email')
      ->setLabel(t('Email'))
      ->setDescription(t('The email of the user who adds the review'))
      ->setRequired(TRUE)
      ->setDefaultValue(NULL)
      ->setSetting('on_label', 'Enabled')
      ->setDisplayOptions('form', [
        'type' => 'email_default',
        'settings' => [
          'display_label' => TRUE,
          'placeholder'=> 'email@example.com',
        ],
        'weight' => 0,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'email_mailto',
      ])
      ->setDisplayConfigurable('view', TRUE);

    //Field, used for input user phone.
    $fields['phone'] = BaseFieldDefinition::create('telephone')
      ->setLabel(t('Phone'))
      ->setDescription(t('The phone of the user who adds the review'))
      ->setRequired(TRUE)
      ->setDefaultValue(NULL)
      ->setDisplayOptions('form', [
        'type' => 'telephone_default',
        'weight' => 10,
        'settings' => [
          'placeholder' => '+380(__)-___-__-__',
        ],
      ])
      ->setPropertyConstraints('value', [
        'Length' => [
          'max' => 18,
          'maxMessage' => t('Phone must be in this format +380(__)-___-__-__'),
        ],
        'Regex' => [
          'pattern' => '/[+](380)\(\d{2}\)-\d{3}-\d{2}-\d{2}$/',
          'message' => t('Mobile number format is +38(___)-___-__-__'),
        ],
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'telephone_link',
        'label' => 'above',
        'weight' => 10,
      ])
      ->setDisplayConfigurable('view', TRUE);

    //Field, used for input user feedback.
    $fields['text'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Feedback'))
      ->setDescription(t('Review'))
      ->setRequired(TRUE)
      ->setDefaultValue(NULL)
      ->setDisplayOptions('form', [
        'type' => 'string_textarea',
        'settings' => [
          'size' => 100,
          'placeholder' => 'You can add feedback here',
        ],
        'weight' => 15,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'text_default',
        'weight' => 15,
      ])
      ->setDisplayConfigurable('view', TRUE);

    //Field, used for upload user image.
    $fields['avatar'] = BaseFieldDefinition::create('image')
      ->setLabel(t('Avatar'))
      ->setDescription(t('The avatar of the user who adds the review'))
      ->setDefaultValue(NULL)
      ->setDisplayOptions('form', [
        'type' => 'image',
        'weight' => 15,
      ])
      ->setSettings([
        'alt_field' => FALSE,
        'max_filesize' => 2097152,
        'file_extensions' => 'jpeg jpg png',
        'file_directory' => 'images/avatars',
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'image',
        'label' => 'hidden',
        'settings' => [
          'image_link' => 'file',
          'image_style' => 'thumbnail',
        ],
      ])
      ->setDisplayConfigurable('view', TRUE);

    //Field, used for upload user image.
    $fields['image'] = BaseFieldDefinition::create('image')
      ->setLabel(t('User image'))
      ->setDescription(t('The custom image of the user who adds the review'))
      ->setDefaultValue(NULL)
      ->setDisplayOptions('form', [
        'type' => 'image',
        'weight' => 16,
      ])
      ->setSettings([
        'alt_field' => FALSE,
        'max_filesize' => 5242880,
        'file_extensions' => 'jpeg jpg png',
        'file_directory' => 'images/review_images',
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'type' => 'image',
        'label' => 'hidden',
        'settings' => [
          'image_link' => 'file',
          'image_style' => 'large',
        ],
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['title'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Title'))
      ->setDescription(t('The title of the alex entity.'))
      ->setRequired(TRUE)
      ->setSetting('max_length', 255)
      ->setDisplayOptions('form', [
        'type' => 'string_textfield',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('view', [
        'label' => 'hidden',
        'type' => 'string',
        'weight' => -5,
      ])
      ->setDisplayConfigurable('view', TRUE);





    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setDescription(t('The time that the alex was created.'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the alex was last edited.'));

    return $fields;
  }

}
