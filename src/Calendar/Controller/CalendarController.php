<?php
/**
 * Created by PhpStorm.
 * User: Tibi
 * Date: 2015.11.25.
 * Time: 7:52
 */

namespace Calendar\Controller;


use Calendar\Helper\WorkTimeDay;
use Calendar\Helper\WorkTimeDayType;
use Calendar\Helper\WorkTimeMonth;
use decoy\base\ActionController;
use decoy\view\ViewModel;

class CalendarController extends ActionController
{

    public function index()
    {
        $this->setTemplate('application/text');
        $view = new ViewModel("calendar/container");


        return $view;
    }

    public function preview()
    {
        $this->setTemplate('application/text');
        $view = new ViewModel('application/index');
        $view->addVariable('moduleName', 'INDEX');
        $view->addVariable('body', 'PREVIEW');
        return $view;
    }

    public function export()
    {

        $this->setTemplate('application/text');
        $view = new ViewModel("calendar/export");
        if ($this->getRequest()->isPost()) {
            $post = $this->forward()->getRequestBody()->getBody();
            $date = new \DateTime($post['year'] . '/' . $post['month'] . '/01');
            $month = new WorkTimeMonth($date);

            $repo = $this->getEntityManager()->getRepository('Application\Entity\Calendar');
            $data = $repo->getMonthExportData($date);
            $dt = $data['d'];

            $day = null;
            $prevDay = '';

            $map = [];
            $prevDay = '';
            foreach ($dt as $d) {

                if ($d->getStartTime() != null && $d->getEntryType()->getContainsTime() == 1) {
                    if ($prevDay == '' || $prevDay != $d->getCalendarDay()->format('Y-m-d')) {
                        if ($prevDay != $d->getCalendarDay()->format('Y-m-d') && $prevDay != '')
                            $month->addDay($day);
                        $day = new WorkTimeDay($d->getCalendarDay());
                    }

                    $day->addType($d->getEntryType()->getEntryTypeId(), new WorkTimeDayType($d));
                    $prevDay = $d->getCalendarDay()->format('Y-m-d');
                }
            }
            $month->addDay($day);


            $repo = $this->getEntityManager()->getRepository('Application\Entity\CalendarEntryType');
            $data = $repo->getList();
            $month->setDayTypeSettings($data);
            $this->save($month);
        }
        return $view;
    }

    public function settings()
    {
        $this->setTemplate('application/text');
        $view = new ViewModel('application/index');
        $view->addVariable('moduleName', 'INDEX');
        $start = new \DateTime('2015-01-30');
        $str = $start->format('Y-m-d');

        $view->addVariable('body', $str);
        return $view;
    }

    public function save(WorkTimeMonth $month)
    {

        $this->getApplication()->getRequestHeader()->setVariable('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $this->getApplication()->getRequestHeader()->setVariable('Content-Disposition', 'attachment;filename="Tálosi Tibor jelenléti ív (' . $month->getDateLabel() . ').xlsx"');
        $this->getApplication()->getRequestHeader()->setVariable('Cache-Control', 'max-age=0');

        $this->getApplication()->getRequestHeader()->setHeaders();

        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator("eDev system")
            ->setLastModifiedBy("eDev system")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Worktime document")
            ->setDescription("Worktime export")
            ->setKeywords("office 2007 openxml php edev")
            ->setCategory("Worktime");
        $month->render($objPHPExcel);
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
        $objWriter->setPreCalculateFormulas();
        $objWriter->save('php://output');
        $this->forward()->disableRender();
        return null;
    }
}