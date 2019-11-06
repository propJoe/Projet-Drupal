<?php

namespace Drupal\hello\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\User\UserInterface;

class HelloUserController extends ControllerBase{
	public function content(UserInterface $user){
		$query = \Drupal::database()->select('hello_user_statistics','hus')->fields('hus', ['action', 'time'])->condition('uid', $user->id());
		$result = $query->execute();
		$rows = [];
		foreach ($result as $record) {
			$rows[] = [
				$record->action == '1' ? $this->t('Login') : $this->t('Logout'),
				\Drupal::service('date.formatter')->format($record->time),
			];
		}
		$table = [
		'#type' => 'table',
  		'#empty' => $this->t('No connections yet'),
  		'#header' => [$this->t('Action'), $this->t('Time')],
  		'#rows' => $rows,
  		];
		return [
			'table' => $table,
			'#cache' => [
				'max-age' => '0',
			]
		];
	}
}