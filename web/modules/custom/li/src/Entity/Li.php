<?php

namespace Drupal\li\Entity;

use   Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\li\LiInterface;
use Drupal\user\UserInterface;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityStorageInterface;


/**
 * Defines the li entity class.
 *
 * @ContentEntityType(
 *   id = "li",
 *   label = @Translation("li"),
 *   label_collection = @Translation("lis"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\li\LiListBuilder",
 *     "views_data" = "Drupal\views\EntityViewsData",
 *     "form" = {
 *       "add" = "Drupal\li\Form\LiForm",
 *       "edit" = "Drupal\li\Form\LiForm",
 *       "delete" = "Drupal\Core\Entity\ContentEntityDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\Core\Entity\Routing\AdminHtmlRouteProvider",
 *     }
 *   },
 *   base_table = "li",
 *   admin_permission = "administer li",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *   },
 *   links = {
 *     "add-form" = "/admin/content/li/add",
 *     "canonical" = "/li/{li}",
 *     "edit-form" = "/admin/content/li/{li}/edit",
 *     "delete-form" = "/admin/content/li/{li}/delete",
 *     "collection" = "/admin/content/li"
 *   },
 *   field_ui_base_route = "entity.li.settings"
 * )
 */
class Li extends ContentEntityBase implements ContentEntityInterface {


  /**
   * {@inheritdoc}
   *
   * When a new li entity is created, set the uid entity reference to
   * the current user as the creator of the entity.
   */

  public static function baseFieldDefinitions(EntityTypeInterface $entity_type): array {

    $fields = parent::baseFieldDefinitions($entity_type);
    // Standard field, used as unique if primary index.
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Advertiser entity.'))
      ->setReadOnly(TRUE);
    // Standard field, unique outside of the scope of the current project.
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
        'file_directory' => 'li/avatars',
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
        'file_directory' => 'li/images',
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

    //Standard field, used for show date and time when feedback was created
    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Authored on'))
      ->setDescription(t('The time when the response was created'))
      ->setDisplayOptions('view', [
        'label' => 'above',
        'type' => 'timestamp',
        'settings' => [
          'date_format' => 'custom',
          'custom_date_format' => 'm/d/Y H:i:s',
        ],
        'weight' => 20,
      ])
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayOptions('form', [
        'type' => 'datetime_timestamp',
        'weight' => 20,
      ])
      ->setDisplayConfigurable('view', TRUE);

    return $fields;
  }

}
