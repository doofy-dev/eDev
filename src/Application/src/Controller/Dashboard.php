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

class Dashboard extends ActionController
{
	public function _Bootstrap()
	{
	}

	public function index()
	{
		$view = new ViewModel('application/index');
		$view->addVariable('moduleName','Áttekintés');
		return $view;
	}
}