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

class Application extends ActionController
{
	public function _Bootstrap()
	{
		if($this->getUrlParameter('type')=='txt')
		{
			$this->setTemplate('application/text');
		}else{
			$this->setTemplate('application');
		}
	}

	public function index()
	{
//		return $this->calendar();
		$this->getApplication()->toUrl('/calendar');
		$view = new ViewModel('application/index');
		$view->addVariable('moduleName','INDEX');
		$view->addVariable('body','INDEX');
		return $view;
	}
	public function dashboard(){
		$view = new ViewModel('application/index');
		$view->addVariable('moduleName','Áttekintés');
		$view->addVariable('body','Áttekintés');
		return $view;
	}
	public function calendar(){
		$view = new ViewModel('application/index');

		$view->addVariable('moduleName','Calendar');
		$view->addVariable('body',$this->forward()->dispatch("Calendar\\Controller\\Calendar",array(
			'action'=>'index'
		)));
		return $view;
	}
}