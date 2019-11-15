<?php

namespace Drupal\hello\Access;

use Drupal\Core\Access\AccessCheckInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;
use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Component\Datetime\TimeInterface;

class HelloAccessCheck implements AccessCheckInterface{
    protected $time;

    public function applies(Route $route){
        return NULL;
    }

    public function _construct(TimeInterface $time){
        $this->time = $time;
    }
    public function access(Route $route, Request $request = NULL, AccountInterface $account){
        $param = $route->getRequirement('_access_hello');
        if(!$account->isAnonymous())
        {
            $created = $account->getAccount()->created;
            $diff = REQUEST_TIME - $created;
            $nbHeure = 3600*$param;
            if($diff >= $nbHeure)
            {
                return AccessResult::allowed()->cachePerUser();
            }
            else{
                return AccessResult::forbidden()->cachePerUser();
            }
        }
        else{
            return AccessResult::forbidden()->cachePerUser();
        }

    }

}