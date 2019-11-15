<?php

namespace Drupal\hello\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

class HelloRouteSubscriber extends RouteSubscriberBase
{
    public function alterRoutes(RouteCollection $collection){
        // get() : recupere une route et setRequirements() modifie la propriete "requirements"
        $route = $collection->get('entity.user.canonical')->setRequirements(['_access_hello' => '10']);
    }
}