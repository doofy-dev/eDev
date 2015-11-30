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

class Calendar extends ActionController
{
	public function index(){
		$this->setTemplate('application/text');
		$view = new ViewModel("calendar/container");



		return $view;
	}


}