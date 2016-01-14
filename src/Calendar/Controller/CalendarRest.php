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

	public function gettasks(){
		if($this->getRequest()->isPost()) {
			$sent = $this->getApplication()->getRequestBody()->content;
			if(array_key_exists('userId',$sent) && array_key_exists('projectId',$sent)){
				$repo = $this->getEntityManager()->getRepository('Application\\Entity\\ProjectTasks');
				return $repo->getTaksList($sent['userId'],$sent['projectId']);
			}
		}
		return array();
	}

	public function savetasks(){
		if($this->getRequest()->isPost()){
			$data = $this->getApplication()->getRequestBody()->content;
			$user = $this->getEntityManager()->getReference('Application\\Entity\\User',$data['userId']);
			$date = new \DateTime($data['date']);

			$repo = $this->getEntityManager()->getRepository('Application\\Entity\\Calendar');
			if($user){
				//Getting saved rows to clean the deleted ones
				$original = $repo->getAllForDate($user,$date);
				foreach($data['values'] as $row){
					if(array_key_exists('calendarId',$row)){
						$repo->updateRow($user, $this->getEntityManager()->getReference('Application\\Entity\\Calendar',$row['calendarId']), $row);
						if(($key = array_search($row['calendarId'], $original)) !== false)
							unset($original[$key]);
					}else{
						$repo->addRow($user,$row,$date);
					}
				}
				$repo->cleanRows($original);

			}
			return array('status'=>true);
		}
	}

}