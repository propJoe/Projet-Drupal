<?php
namespace Drupal\hello\Plugin\Block;
use Drupal\Core\Block\BlockBase;
 
/**
* Provides a hello block.
*
* @Block(
*  id = "hello_block",
*  admin_label = @Translation("Hello!")
* )
*/
class Block extends BlockBase {
	/**
	* Implements Drupal\Core\Block\BlockBase::build().
	*/
	public function build(){
		$dateFormatter = \Drupal::service('date.formatter');
		$time = \Drupal::service('datetime.time')->getCurrentTime();
		// recupere le name de l'uilisateur courant
		$userName = \Drupal::currentUser()->getDisplayName(); 
		return [
			'#markup' => $this->t('Bienvenue %name, il est %time',[
				'%name' => $userName,
				'%time' => $dateFormatter->format($time, 'custom','H:i s\s'),
			]),
			'#cache' => [
				'max-age' => '0',
			]
		];
	}
}