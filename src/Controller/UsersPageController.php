<?php

namespace Drupal\accredible_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\taxonomy\Entity\Term;

class UsersPageController extends ControllerBase {

/**
 * Returns a render array for a table of credential nodes.
 */
public function credentialTable() {
  // Load all nodes of type 'credential'.
  $nids = \Drupal::entityQuery('node')
    ->condition('type', 'credential')
    ->accessCheck(TRUE)
    ->execute();
  $nodes = Node::loadMultiple($nids);
  //ksm($nodes[15]->get('field_credential_id')->value);
  ksm($nodes[15]);

  // Prepare table header.
  $header = [
    'title' => t('Title'),
    'created' => t('Created'),
    'author' => t('Author'),
    'date' => t('Date'),
    'certification_type' => t('Certification')
    // Add other fields as needed.
  ];

  // Prepare table rows.
  $rows = [];
  
  foreach ($nodes as $node) {

    // Load Term Name
    $term = $node->get('field_certification_type')->entity;
    if ($term) {
        $term_name = $term->getName();
    }

    $rows[] = [
        'title' => $node->getTitle(),
        'created' => \Drupal::service('date.formatter')->format($node->getCreatedTime(), 'short'),
        'author' => $node->getOwner()->getDisplayName(),
        'date' => $node->get('field_date')->value,
        'certification_type' => $term_name
        // Add other fields as needed.
      ];
    }
  
    // Create a render array for the table.
    $build = [
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#empty' => t('No credentials found'),
    ];
  
    return $build;
     

}
}