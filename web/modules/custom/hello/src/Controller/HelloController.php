<?php

namespace Drupal\hello\Controller;
use Drupal\Core\Controller\ControllerBase;

class HelloController extends ControllerBase
{
  public function content(){
    return ['#markup' => $this->t('Hello ! '.$this->currentUser()->getDisplayName())];
  }
}
