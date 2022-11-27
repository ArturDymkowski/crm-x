<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class WorkOrderHtmlExport implements FromCollection, WithHeadings
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect($this->data);
    }

    public function headings():array
    {
        return [
            'ticket',
            'entityid',
            'urgancy',
            'rcvd_date',
            'category',
            'story_name',
            'status',
        ];
    }
}
