<?php

namespace Drupal\hello\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Drupal\Core\Link;

class Hello2Controller extends ControllerBase
{
  public function content($nodetype = null){
  	$nodes_type_storage = $this->entityTypeManager()->getStorage('node_type')->loadMultiple();
  	//ksm($nodes_type_storage);
  	$node_type_items = [];
  	foreach ($nodes_type_storage as $node_type) {
  		$url = new Url('hello.hello2',['nodetype' => $node_type->id()]);
  		$node_type_link = new Link($node_type->label(), $url);

  		// $node_type->label(); => recupere le nom de chaque type de contenu.
  		$node_type_items[] = $node_type_link;
  	}
  	$node_type_list = [
  		'#theme' => 'item_list',
  		'#items' => $node_type_items,
  		'#title' => $this->t('Filtrer by node type'),
  	];


  	//recupere un objet manager pour pouvoir manipuler le type d'entity passer en parametre de la fonction getStorage() = > node par exemple
  	$nodes_storage = $this->entityTypeManager()->getStorage('node');
  	//permet de faire des requete sur les noeuds
  	$query = $nodes_storage->getQuery();
  	if($nodetype){
  		$query->condition('type',$nodetype);
  	}
  	// retourne un tableau d'id des noeuds
  	$nodesIds = $query->pager()->execute();
  	//recupere les noeuds grace au tableau d'ids en parametre
  	$nodes = $nodes_storage->loadMultiple($nodesIds);
  	$items = [];
  	foreach ($nodes as $node) {
  		// crée un lien vers chaque noeud.
  		$items[] = $node->toLink();
  	}
  	//ksm($nodes);

  	// $list & $pager => renderarray https://www.drupal.org/docs/8/api/render-api/render-arrays

  	//'item_list' => pour utiliser des balises ul-li
    $list =  [
    	'#theme' => 'item_list',
    	'#items' => $items,
    ];
    $pager = ['#type' => 'pager'];
    return [
    	'node_type_list' => $node_type_list,
    	'list' => $list,
    	'pager' => $pager,
    	'#cache' => ['max-age' => '0']
    ];
  }
}
