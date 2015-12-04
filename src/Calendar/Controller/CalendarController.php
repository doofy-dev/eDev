<?php
/**
 * Created by PhpStorm.
 * User: Tibi
 * Date: 2015.11.25.
 * Time: 7:52
 */

namespace Calendar\Controller;


use decoy\base\ActionController;
use decoy\view\ViewModel;

class CalendarController extends ActionController
{
	public function index(){
		$this->setTemplate('application/text');
		$view = new ViewModel("calendar/container");
		return $view;
	}

	public function preview(){
		$this->setTemplate('application/text');
		$view = new ViewModel('application/index');
		$view->addVariable('moduleName','INDEX');
		$view->addVariable('body','PREVIEW');
		return $view;
	}

	public function export(){
		$this->setTemplate('application/text');
		$view = new ViewModel('application/index');
		$view->addVariable('moduleName','INDEX');
		$view->addVariable('body','EXPORT');
		return $view;
	}

	public function settings(){
		$this->setTemplate('application/text');
		$view = new ViewModel('application/index');
		$view->addVariable('moduleName','INDEX');
		$view->addVariable('body','SETTINGS');
		return $view;
	}

}