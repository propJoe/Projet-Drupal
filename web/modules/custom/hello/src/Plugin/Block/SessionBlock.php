<?php
namespace Drupal\hello\Plugin\Block;
use Drupal\Core\Block\BlockBase;
 
/**
* Provides a hello block.
*
* @Block(
*  id = "session_block",
*  admin_label = @Translation("Session Block")
* )
*/
class SessionBlock extends BlockBase {
	/**
	* Implements Drupal\Core\Block\BlockBase::build().
	*/
	public function build(){
		$database = \Drupal::database();
		$session = $database->select('sessions')->countQuery()->execute()->fetchField();
		return [
			'#markup' => $this->t('Il y a actuellement %nb session actives.',[
				'%nb' => $session,
			]),
			'#cache' => [
				'max-age' => '0',
			]
		];
	}
}