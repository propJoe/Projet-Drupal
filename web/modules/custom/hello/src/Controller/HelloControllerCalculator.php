<?php
/**
 * Created by PhpStorm.
 * User: Formation 19
 * Date: 12/11/2019
 * Time: 10:05
 */
namespace Drupal\hello\Controller;
use Drupal\Core\Controller\ControllerBase;

class HelloControllerCalculator extends ControllerBase
{
    public function content(){

        return ['#markup' => $this->t('Calculator '.$this->currentUser()->getDisplayName())];
    }
}

