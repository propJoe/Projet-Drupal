<?php

namespace Drupal\annonce\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Annonce entities.
 */
class AnnonceViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();
    $data['annonce_user_views']['table']['group'] = t('Annonce history');
    $data['annonce_user_views']['table']['provider'] = 'annonce';
    $data['annonce_user_views']['table']['base'] = [
      // Identifier (primary) field in this table for Views.
      'field' => 'id',
      // Label in the UI.
      'title' => t('Annonce history'),
      // Longer description in the UI. Required.
      'help' => t('Annonce history contanis historical datas and be related to annonces.'),
    ];
      $data['annonce_user_views']['uid'] = [
          'title' => t('Annonce user ID'),
          'help' => t('Annonce user ID'),
          'field' => ['id' => 'numeric'],
          'sort' => ['id' => 'standard'],
          'filter' => ['id' => 'numeric'],
          'argument' => ['id' => 'numeric'],
          // Define a relationship to the node_field_data table, so views whose
          // base table is example_table can add a relationship to nodes. To make a
          // relationship in the other direction, you can:
          // - Use hook_views_data_alter() -- see the function body example on that
          //   hook for details.
          // - Use the implicit join method described above.
          'relationship' => [
              // Views name of the table to join to for the relationship.
              'base' => 'users_field_data',
              // Database field name in the other table to join on.
              'base field' => 'uid',
              // ID of relationship handler plugin to use.
              'id' => 'standard',
              // Default label for relationship in the UI.
              'label' => t('Annonce history UID -> user ID'),
          ],
      ];
      $data['annonce_user_views']['time'] = [
          'title' => t('Date time of the visit'),
          'help' => t('Date time of the visit'),
          'field' => ['id' => 'date'],
          'sort' => ['id' => 'date'],
          'filter' => ['id' => 'date'],
      ];
      $data['annonce_user_views']['aid'] = [
          'title' => t('Annonce ID'),
          'help' => t('Annonce ID'),
          'field' => ['id' => 'numeric'],
          'sort' => ['id' => 'standard'],
          'filter' => ['id' => 'numeric'],
          'argument' => ['id' => 'numeric'],
          // Define a relationship to the node_field_data table, so views whose
          // base table is example_table can add a relationship to nodes. To make a
          // relationship in the other direction, you can:
          // - Use hook_views_data_alter() -- see the function body example on that
          //   hook for details.
          // - Use the implicit join method described above.
          'relationship' => [
              // Views name of the table to join to for the relationship.
              'base' => 'annonce_field_data',
              // Database field name in the other table to join on.
              'base field' => 'id',
              // ID of relationship handler plugin to use.
              'id' => 'standard',
              // Default label for relationship in the UI.
              'label' => t('Annonce history AID -> annonce ID'),
          ],
      ];

      // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}
