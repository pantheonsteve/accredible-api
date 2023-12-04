<?php

namespace Drupal\accredible_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;


/**
 * Controller routines for page example routes.
 */
class AccredibleAPIController extends ControllerBase {

    //use DescriptionTemplateTrait;
  
    /**
     * {@inheritdoc}
     */
    protected function getModuleName() {
      return 'accredible_api';
    }

    public function fetch_accredible_credentials() {
      $accredible_client = \Drupal::service('accredible_api.api_client');
      $response = $accredible_client -> get_accredible_credentials();

      // Render the results
      // Extract the relevant data from the $response array
      $rows = [];
      foreach ($response['credentials'] as $item) {
        $agency = isset($item['custom_attributes']['Agency']) ? $item['custom_attributes']['Agency'] : 'N/A'; // Default to 'N/A' if agency is not set
        $rows[] = [
          $item['recipient_name'],
          $item['recipient_email'],
          $agency,
        ];
      }

      // Render the results in a table format
      $output = [
          '#theme' => 'table',
          '#header' => [$this->t('Name'), $this->t('Email'), $this->t('Agency')],
          '#rows' => $rows,
          '#empty' => $this->t('No results found'),
      ];

      return $output;
    }
    
    public function get_accredible_credential_by_id($credential_id) {
        $accredible_client = \Drupal::service('accredible_api.api_client');
        $response = $accredible_client->get_credential_by_id($credential_id);
    
        // Assuming the response structure is as you've shown, 
        // and you want the URL of the first credential
        if (isset($response['credential']['url'])) {
            return new Response($response['credential']['url']);
        }
    
        // Return some default or error message if the URL isn't found
        return new Response('URL not found');
    }
}