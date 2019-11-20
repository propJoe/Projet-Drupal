<?php

namespace Drupal\date_condition\Plugin\Condition;

use Drupal\Core\Condition\ConditionPluginBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\Context\ContextDefinition;
use Drupal\Core\Datetime\DrupalDateTime;

/**
 * Provides a 'Date' condition to enable a condition based in module selected status.
 *
 * @Condition(
 *   id = "date_condition",
 *   label = @Translation("Date"),
 *   context = {
 *     "annonce" = @ContextDefinition("entity:annonce", required = FALSE , label = @Translation("annonce"))
 *   }
 * )
 *
 */
class DateCondition extends ConditionPluginBase {

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition
    );
  }

  /**
   * Creates a new DateCondition object.
   *
   * @param array $configuration
   *   The plugin configuration, i.e. an array with configuration values keyed
   *   by configuration option name. The special key 'context' may be used to
   *   initialize the defined contexts by setting it to an array of context
   *   values keyed by context names.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public function buildConfigurationForm(array $form, FormStateInterface $form_state) {
    // Sort all modules by their names.
    $form['date_start'] = [
         '#type' => 'date',
         '#title' => $this->t('Date start'),
         '#default_value' => $form_state->getValue('date_start'),
    ];
    $form['date_end'] = [
        '#type' => 'date',
        '#title' => $this->t('Date end'),
        '#default_value' => $form_state->getValue('date_end'),
    ];
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitConfigurationForm(array &$form, FormStateInterface $form_state) {
    $this->configuration['date_start'] = $form_state->getValue('date_start');
    $this->configuration['date_end'] = $form_state->getValue('date_end');
    parent::submitConfigurationForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function defaultConfiguration() {
    return [
            'date_start' => '',
            'date_end' => '',
        ] + parent::defaultConfiguration();
  }

  /**
   * Evaluates the condition and returns TRUE or FALSE accordingly.
   *
   * @return bool
   *   TRUE if the condition has been met, FALSE otherwise.
   */
  public function evaluate() {

    $today = new DrupalDateTime('today');
    $start = $this->configuration['date_start'] ?  new DrupalDateTime($this->configuration['date_start']) : NULL;
    $end = $this->configuration['date_end'] ?  new DrupalDateTime($this->configuration['date_end']) : NULL;

      // si il n'y a pas de date de $start OU que $start est <= a today
      // ET
      // s'il n'y a pas de date de $end  OU que $end est >= a la date $today
    return (!$start || ($start <= $today) && !$end || ($end >= $today) );
  }

  /**
   * Provides a human readable summary of the condition's configuration.
   */
  public function summary() {
    $module = $this->getContextValue('module');
    $modules = system_rebuild_module_data();

    $status = ($modules[$module]->status)?t('enabled'):t('disabled');

    return t('The module @module is @status.', ['@module' => $module, '@status' => $status]);
  }


  public function validateConfigurationForm(array &$form, FormStateInterface $form_state){
      if(!empty($form_state->getValue('date_start')) && !empty($form_state->getValue('date_end'))){
          $start = new DrupalDateTime($form_state->getValue('date_start'));
          $end = new DrupalDateTime($form_state->getValue('date_end'));
          // || $end < new DrupalDateTime('today')
          if($end < $start){
              $form_state->setErrorByName('end_date', $this->t('End date error !'));
          }
      }
  }
}
