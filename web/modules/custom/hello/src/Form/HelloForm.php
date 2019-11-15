<?php


namespace Drupal\hello\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;

class HelloForm extends FormBase
{
    public function getFormId(){
        return 'hello_form';
    }
    public function validateNbAjax(array &$form, FormStateInterface $form_state){

        $response = new AjaxResponse();
        $field = $form_state->getTriggeringElement()['#name'];
        if(!is_numeric($form_state->getValue($field)))
        {
            $message = 'Ajax message: La valeur du '.$field.' n\'est pas un chiffre.';
            $css = ['border' => '2px solid red'];
        }else{
            $message = 'Ajax message: '.$form_state->getValue($field);
            $css = ['border' => '2px solid green'];
        }
        $response->addCommand(new CssCommand("[name=$field]", $css));
        $i++;
        $response->addCommand(new HtmlCommand('#error-message-' . $field, $message));
        return $response;
    }

    public function buildForm(array $form, FormStateInterface $form_state){
        if(isset($form_state->getRebuildInfo()['result'])){
            $form['result'] = [
                '#type' => 'html_tag',
                '#tag' => 'h2',
                '#value' => $this->t('Result: '.$form_state->getRebuildInfo()['result']),
            ];
        }
        $form['nb1'] = array(
            '#type' => 'textfield',
            '#title' => 'Nombre 1',
            '#description' => 'Entrer une première valeur.',
            '#ajax' => array(
                'callback' => array($this, 'validateNbAjax'),
                'event' => 'change',
            ),
            '#suffix' => '<span id="error-message-nb1"></span>'
        );

        $form['queued_items'] = array(
            '#type' => 'select',
            '#options' => array(
                'ajouter' => 'Ajouter',
                'soustraire' => 'Soustraire',
                'multiplier' => 'Multiplier',
                'diviser' => 'Diviser',
            ),
            '#default_value' => 'ajouter',
            '#attribute' => ['class' => ['items-number']],
            '#description' => 'Choisir l\'operateur de calcul.'
        );


        $form['nb2'] = array(
            '#type' => 'textfield',
            '#title' => 'Nombre 2',
            '#description' => 'Entrer une deuxième valeur.',
            '#ajax' => array(
                'callback' => array($this, 'validateNbAjax'),
                'event' => 'change',
            ),
            '#suffix' => '<span id="error-message-nb2"></span>'
        );

        $form['create_items'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Calculer'),
        );
        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state){
        $nb1 = $form_state->getValue('nb1');
        $operation = $form_state->getValue('queued_items');
        $nb2 = $form_state->getValue('nb2');

        $tab = [];
        $tab[] = $nb1;
        $tab[] = $nb2;
        $tab[] = $operation;
        //ksm($form_state);
        //ksm($tab);

        $resultat = '';
        if(!empty($nb1) && !empty($nb2)){
            if($operation === 'soustraire')
                $resultat = $nb1-$nb2;
            if($operation === 'ajouter')
                $resultat = $nb1+$nb2;
            if($operation === 'multiplier')
                $resultat = $nb1*$nb2;
            if($operation === 'diviser' && $nb1 != 0){
                $resultat = $nb1/$nb2;
            }
            else{
                //$form_state->setErrorByName('nb1', $this->t('Le nombre 1 doit être différent de zero et être un numeriqe'));
            }
        }
        // Enregistrement du timestamp de la derniere requete effectuer.
        \Drupal::state()->set('hello_last_submission_time', REQUEST_TIME);


        //$res ='';
        //$this->messenger()->addMessage($resultat);
        // On passe l eresultat
        $form_state->addRebuildInfo('result',$resultat);

        // Reconstruction du formulaire
        $form_state->setRebuild();
    }
}