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
		$start = new \DateTime('2015-01-30');
		$str = $start->format('Y-m-d');

		$view->addVariable('body',$str);
		return $view;
	}


	public function save(){



		$this->getApplication()->getRequestHeader()->setVariable('Content-Type','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		$this->getApplication()->getRequestHeader()->setVariable('Content-Disposition','attachment;filename="01simple.xlsx"');
		$this->getApplication()->getRequestHeader()->setVariable('Cache-Control','max-age=0');

		$this->getApplication()->getRequestHeader()->setHeaders();

		$objPHPExcel = new \PHPExcel();
		$objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
				->setLastModifiedBy("Maarten Balliauw")
				->setTitle("Office 2007 XLSX Test Document")
				->setSubject("Office 2007 XLSX Test Document")
				->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
				->setKeywords("office 2007 openxml php")
				->setCategory("Test result file");


// Add some data
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A1', 'Hello')
				->setCellValue('B2', 'world!')
				->setCellValue('C1', 'Hello')
				->setCellValue('D2', 'world!');

// Miscellaneous glyphs, UTF-8
		$objPHPExcel->setActiveSheetIndex(0)
				->setCellValue('A4', 'Miscellaneous glyphs')
				->setCellValue('A5', 'éàèùâêîôûëïüÿäöüç');

// Rename worksheet
		$objPHPExcel->getActiveSheet()->setTitle('Simple');


// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$objPHPExcel->setActiveSheetIndex(0);


		$objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');
		$this->forward()->disableRender();
		return null;
	}
}