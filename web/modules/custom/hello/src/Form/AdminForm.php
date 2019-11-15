<?php

namespace Drupal\hello\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

class AdminForm extends ConfigFormBase
{
    public function getFormID(){
        return 'admin_form';
    }
    protected function getEditableConfigNames()
    {
        return ['hello.settings'];
    }
    public function buildForm(array $form, FormStateInterface $form_state){
        $purge_days_number = $this->config('hello.settings')->get('purge_days_number');
        $form['purge_days_number'] = array(
            '#type' => 'select',
            '#options' => array(
                '0' => 'Never purge',
                '1' => 'One week',
                '2' => 'Two days',
                '7' => 'One week',
                '14' => 'Two Week',
                '30' => 'One month',
            ),
            '#default_value' => $purge_days_number,
            '#description' => 'Choisir le rythme de purge.'
        );
        return parent::buildForm($form, $form_state);
    }
    public function submitForm(array &$form, FormStateInterface $form_state){
        $this->config('hello.settings')->set('purge_days_number', $form_state->getValue('purge_days_number'))->save();
        parent::submitForm($form, $form_state);
    }

}