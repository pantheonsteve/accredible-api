<?php

namespace Drupal\accredible_api;

use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Class AccredibleClient
 */
class AccredibleClient {

    /**
     * HTTP Client for the Accredible API
     * 
     */

    protected $configFactory;

    public function __construct(ConfigFactoryInterface $config_factory)
    {
        $this->configFactory = $config_factory;
    }

     public function create_accredible_credential($email, $name, $agency, $date, $certification_type, $group_id) {
        $client = \Drupal::httpClient();
        $config = $this->configFactory->get('accredible_api.settings');
        $url = "https://api.accredible.com/v1/credentials/";
        

        $body = [
                    'credential' => [
                        'recipient' => [
                            'name' => $name,
                            'email' => $email
                        ],
                        'name' => $certification_type,
                        'group_id' => $group_id,
                        'description' => null,
                        'issued_on' => $date,
                        'custom_attributes' => [
                            'agency' => $agency
                        ]
                    ]
                ];
        
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Token token=' . $config->get('accredible_api_key'),
            'Host' => 'api.accredible.com'
        ];

        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'body' => json_encode($body)
            ]);

            // Decode the JSON response.
            $data = json_decode($response->getBody(), TRUE);

            // TO-DO: process this data further
            return $data;
            
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle the exception, e.g., log it or display an error message.
            \Drupal::messenger()->addError(t('An error occurred while fetching data from Accredible: @error', ['@error' => $e->getMessage()]));
            return [];
        }
        
    }

     public function get_accredible_credentials() {

        $config = $this->configFactory->get('accredible_api.settings');

        $client = \Drupal::httpClient();

        $url = "https://api.accredible.com/v2/credentials/search";

        $body = [
            'query' => [
                'group.id' => $config->get('group_id'),
                'issued_on[gt]' => '2023-01-01',
                'issued_on[lte]' => '2023-12-31'
            ],
            'page' => [
                'from' => 1,
                'size' => 100,
            ],
        ];

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Token token=' . $config->get('accredible_api_key'),
            'Host' => 'api.accredible.com'
        ];

        try {
            $response = $client->post($url, [
                'headers' => $headers,
                'body' => json_encode($body)
            ]);

            // Decode the JSON response.
            $data = json_decode($response->getBody(), TRUE);

            // TO-DO: process this data further
            return $data;
            
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle the exception, e.g., log it or display an error message.
            \Drupal::messenger()->addError(t('An error occurred while fetching data from Accredible: @error', ['@error' => $e->getMessage()]));
            return [];
          }
    }

    public function get_credential_by_id($credential_id) {
        $config = $this->configFactory->get('accredible_api.settings');
        $client = \Drupal::httpClient();
        $url = "https://api.accredible.com/v1/credentials/" . $credential_id;

        $body = [];

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Token token=' . $config->get('accredible_api_key'),
            'Host' => 'api.accredible.com'
        ];

        try {
            $response = $client->get($url, [
                'headers' => $headers,
                'body' => json_encode($body)
            ]);

            // Decode the JSON response.
            $data = json_decode($response->getBody(), TRUE);

            // TO-DO: process this data further
            return $data;
            
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Handle the exception, e.g., log it or display an error message.
            \Drupal::messenger()->addError(t('An error occurred while fetching data from Accredible: @error', ['@error' => $e->getMessage()]));
            return [];
          }

    }
}