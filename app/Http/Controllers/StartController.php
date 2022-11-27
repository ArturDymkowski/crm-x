<?php

namespace App\Http\Controllers;

use App\Exports\WorkOrderHtmlExport;
use App\Http\Requests\Request;
use App\Modules\WorkOrder\Models\WorkOrder;
use App\Modules\WorkOrder\Repositories\WorkOrderRepository;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\DomCrawler\Crawler;

class StartController extends Controller
{
    private $workOrderRepository;

    public function __construct(WorkOrderRepository $workOrderRepository)
    {
        $this->workOrderRepository = $workOrderRepository;
    }

    public function getApp()
    {
        return view('start.index', [
            'logs' => DB::table('importer_log')
                ->orderBy('id','DESC')
                ->paginate(25)
        ]);
    }
    
    public function getAppOld()
    {
        return view('start.indexOld');
    }

    public function getTest()
    {
        $options = [
            'margin-top' => 14, // in mm
            'margin-bottom' => 10, // in mm
            'margin-left' => 0,
            'margin-right' => 0,
            //    'header-spacing' => 20 // in mm
        ];

        $data = [
            'companyName' => 'My test company',
        ];

        $pdf = \PDF::loadView('pdfs.fax', $data);
        $pdf->setPaper('A4')
            ->setOptions($options)
            ->setHeader($data)
            ->setFooter($data);

        return $pdf->stream('test.pdf');
    }

    public function ordersWorkerImporter()
    {
        $crawler = new Crawler(file_get_contents(base_path() . '\work_orders.html'));

        $tables = [
            'table#ctl00_ctl00_ContentPlaceHolderMain_MainContent_TicketLists_AllTickets_ctl00',
            'table#ctl00_ctl00_ContentPlaceHolderMain_MainContent_TicketLists_PaperworkTickets_ctl00',
            'table#ctl00_ctl00_ContentPlaceHolderMain_MainContent_TicketLists_OpenTickets_ctl00',
        ];

        $data = [];

        if (!empty($tables)) {
            foreach ($tables as $tableId) {
                foreach($crawler->filter($tableId . " tbody tr") as $tr) {
                    $data_tmp = [];
                    $tdCrawler = new Crawler($tr);
                    $i = 0;
                    foreach($tdCrawler->filter("td") as $td) {
                        if ($i == 0) {
                            $entityCrawler = new Crawler($td);
                            $entity = $entityCrawler->filter('a')->attr('href');
                            $tmp = explode("=",$entity);
                            $data_tmp[] = $tmp[1];
                        }
                        $data_tmp[] = trim($td->textContent);
                        $i++;
                    }
                    $data[] = self::sort_by_table($tableId,$data_tmp);
                }
            }
        }

        $data = $this->workOrderRepository->addDataFromHtml($data);

        return Excel::download(new WorkOrderHtmlExport($data), 'work_order.csv');
    }

    private function sort_by_table($tableId, $data):array {
        switch ($tableId) {

            case 'table#ctl00_ctl00_ContentPlaceHolderMain_MainContent_TicketLists_OpenTickets_ctl00':
                $sorted = [
                    'work_order_number' => $data[1],
                    'external_id' => $data[0],
                    'priority' => null,
                    'received_date' => date('Y-m-d', strtotime($data[2])),
                    'category' => $data[6],
                    'fin_loc' => $data[8],
                ];
                break;

            default:
                $sorted = [
                    'work_order_number' => $data[1],
                    'external_id' => $data[0],
                    'priority' => $data[4],
                    'received_date' => date('Y-m-d', strtotime($data[5])),
                    'category' => $data[9],
                    'fin_loc' => $data[11],
                ];
                break;
        }

        return $sorted;
    }
}
