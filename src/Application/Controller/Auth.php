<?php
/**
 * Created by PhpStorm.
 * User: Tibi
 * Date: 2015.11.17.
 * Time: 9:46
 */

namespace Application\Controller;


use decoy\base\ActionController;
use decoy\view\ViewModel;

class Auth extends ActionController
{
	public function _Bootstrap()
	{
	}

	public function login()
	{
		$view = new ViewModel('application/auth');
		$view->addVariables(array(
			'alma'=>'ez egy alma',
			'korte'=>'ez meg egy körte'
		));
		return $view;
	}
	public function logout(){
		return new ViewModel('application/auth');
	}
}