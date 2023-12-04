<?php

namespace Drupal\accredible_api\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\Response;

/**
 * Controller routines for Credential edit pages.
 */
class CredentialController extends ControllerBase {

    public function create_credential($email, $name, $agency, $date, $certification_type, $group_id) {
        
        $accredible_client = \Drupal::service('accredible_api.api_client');
        $response = $accredible_client->create_accredible_credential($email, $name, $agency, $date, $certification_type, $group_id);

        // Decode the JSON response
        $data = $response;

        // Check if the response contains the 'credential' key
        if (isset($data['credential'])) {
            $credential = $data['credential'];

            // Extract the relevant data
            $credential_id = $credential['id'];
            $credential_url = $credential['url'];

        
        // Assuming the API response contains the Credential ID and URL.
        return [
            'credential_id' => $credential_id,
            'credential_url' => $credential_url,
        ];
    }
}
}
