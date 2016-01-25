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
use decoy\log\Logger;
use decoy\view\ViewModel;
use PHPExcel_CachedObjectStorageFactory;
use PHPExcel_Settings;

class CalendarController extends ActionController
{

    public function index()
    {
        $this->setTemplate('application/text');
        $this->translator->setLocale('hu_HU');
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
        $logger = new Logger();
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
                $logger->Log('log/loop.txt',json_encode($d->toArray()));
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
        $view = new ViewModel('calendar/settings');
        $view->addVariable('moduleName', 'INDEX');
        $start = new \DateTime('2015-01-30');
        $str = $start->format('Y-m-d');

        $view->addVariable('body', $str);
        return $view;
    }

    public function save(WorkTimeMonth $month)
    {
        ob_start();
//        error_reporting(E_ALL);
//        ini_set('display_errors', TRUE);
//        ini_set('display_startup_errors', TRUE);

        ini_set('memory_limit', '512M');
        ini_set('max_execution_time', '10000');
        set_time_limit(10000);
        $cacheMethod = PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;
        $cacheSettings = array('memoryCacheSize' => '512M');
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);
        $objPHPExcel = new \PHPExcel();
        $objPHPExcel->getProperties()->setCreator("eDev system")
            ->setLastModifiedBy("eDev system")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Worktime document")
            ->setDescription("Worktime export")
            ->setKeywords("office 2007 openxml php edev")
            ->setCategory("Worktime");
        $month->render($objPHPExcel);

//        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'CSV');
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
//
        $this->getApplication()->getRequestHeader()->setVariable('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
////        $this->getApplication()->getRequestHeader()->setVariable('Content-Type', 'text/csv');
//        $this->getApplication()->getRequestHeader()->setVariable('Content-Disposition', 'attachment;filename="Tálosi Tibor jelenléti ív (' . $month->getDateLabel() . ').xlsx"');
        $this->getApplication()->getRequestHeader()->setVariable('Cache-Control', 'max-age=0');
//
        $this->getApplication()->getRequestHeader()->setHeaders();

        $objWriter->setPreCalculateFormulas();
        ob_end_clean();
//        echo '<pre>';
//        var_dump($objPHPExcel);
//        echo '</pre>';


        Logger::Log('log/memory.txt', "Memory usage: " . (memory_get_peak_usage(true) / 1024 / 1024) . " MB");
//        $objWriter->save('php://output');
        $objWriter->save('export/TT_woktime (' . $month->getDateLabel() . ').xlsx');
        $this->forward()->disableRender();
        return null;
    }
}