<?php
namespace Drupal\accredible_api\Form;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class AccredibleAPISettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */

  protected $configFactory;

  public function __construct(ConfigFactoryInterface $configFactory)
  {
    $this->configFactory = $configFactory;
  }

  protected function getEditableConfigNames() {
    return ['accredible_api.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'accredible_api_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->configFactory->get('accredible_api.settings');

    $form['accredible_api_key'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Accredible API Key'),
        '#default_value' => $config->get('accredible_api_key'),
        '#required' => TRUE,
      ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('accredible_api.settings')
      ->set('accredible_api_key', $form_state->getValue('accredible_api_key'))
      ->save();

    parent::submitForm($form, $form_state);
  }
}
