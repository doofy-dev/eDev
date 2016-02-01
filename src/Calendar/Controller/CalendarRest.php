<?php
/**
 * eDev.
 * User: Tibi
 * Date: 2015.11.30.
 * Time: 13:00
 */

namespace Calendar\Controller;


use decoy\base\RestFulController;
use decoy\log\Logger;

class CalendarRest extends RestFulController
{

	public function getsummary()
	{
		$date = new \DateTime();
		$sent = $this->getApplication()->getRequestBody()->content;
		if (array_key_exists('date', $sent)) {
			$date = new \DateTime($sent['date']);
		}
		$calendarRepo = $this->getEntityManager()->getRepository('Application\\Entity\\Calendar');
		$result = $calendarRepo->getMonth($date);

		$map = array();

		foreach ($result as $item) {
			$id=-1;
			for($i=0;$i<count($map);$i++){
				if($map[$i]['label']==$item['entryName']) {
					$id=$i;
					break;
				}
			}
			if($id==-1) {
				$map[] = array('label' => $item['entryName'], 'data' => array());
				$id = count($map)-1;
			}
			if($item['end']['hour'] == "" && $item['end']['min'] == "" && $item['start']['hour'] == "" && $item['start']['min'] == "")
			{
				$item['end']['hour'] = 0;
				$item['end']['min'] = 0;
				$item['start']['hour'] = 0;
				$item['start']['min'] = 0;
			}
			if ($item['end']['hour'] == "" && $item['start']['hour'] != "") $item['end']['hour'] = 24;
			if ($item['end']['min'] == "") $item['end']['min'] = 0;
			if ($item['start']['hour'] == "") $item['start']['hour'] = 0;
			if ($item['start']['min'] == "") $item['start']['min'] = 0;
			$mID=-1;
			for($i=0;$i<count($map[$id]['data']);$i++){
				if($map[$id]['data'][$i][0]== $item['calendarDay']){
					$mID = $i;
					break;
				}
			}
			$sim = ($item['end']['hour'] + $item['end']['min'] / 60) - ($item['start']['hour'] + $item['start']['min'] / 60);
			if($mID==-1){
				$map[$id]['data'][] = array($item['calendarDay'], $sim);
			}
			else{
				$map[$id]['data'][$mID][1] += $sim;
			}
		}


		Logger::Log('log/log.txt',json_encode($result,JSON_PRETTY_PRINT));
	return $map;
			return array(
			array('label'=>'alma','data'=>array(array(1445767860000, 20), array(1445860800000, 30)))
		);
	}

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