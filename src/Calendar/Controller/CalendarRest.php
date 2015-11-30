<?php
/**
 * eDev.
 * User: Tibi
 * Date: 2015.11.30.
 * Time: 13:00
 */

namespace Calendar\Controller;


use decoy\base\RestFulController;

class CalendarRest extends RestFulController
{
	public function getdata(){
		if($this->getRequest()->isPost()){

			$sent = $this->getApplication()->getRequestBody()->content;
			$date = new \DateTime();
			if(array_key_exists('date',$sent)){
				$date = new \DateTime($sent['date']);
			}
			$calendarRepo = $this->getEntityManager()->getRepository('Application\\Entity\\Calendar');
			$result = $calendarRepo->getMonth($date);
//			print_r($result);
			return $result;
		}
		return array('type'=>'NOTHING SENT');
	}

	public function gettypes(){
		$repo = $this->getEntityManager()->getRepository('Application\\Entity\\CalendarEntryType');
		return $repo->getList();
	}

	public function getprojects(){
		$repo = $this->getEntityManager()->getRepository('Application\\Entity\\ProjectList');
		return $repo->getProjectsForUser(1);
	}
}