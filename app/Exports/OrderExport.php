<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Order_item;
use DateTime;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;


use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeImport;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;

class OrderExport implements FromView, ShouldAutoSize,  Responsable, WithEvents
{
    use Exportable;

    public $date1; 
    public $date2;

    public function __construct($date1, $date2){
        $this->date1 = $date1;
        $this->date2 = $date2;
    }


    public function view():view
    {
        if($this->date1){
            $start = date('Y-m-d',strtotime(str_replace('-','/',$this->date1)));
            $end = date('Y-m-d',strtotime(str_replace('-','/',$this->date2)));
            $order_ids = Order::whereBetween('order_date', [$start, $end])->select('id')->get()->toArray();
            // dd($order_ids);
            $orders =Order_item::whereIn('order_id', $order_ids)->orderBy('created_at', 'DESC')->get();
            // dd($orders);
        }else{
            $orders= Order_item::orderBy('created_at', 'DESC')->get();
        }
        // dd($orders);
        return view('common.export.order',['orders'=>$orders]);
    }

    public function view2():view
    {
        if($this->date1){
            $start   = date('Y-m-d',strtotime(str_replace('-','/',$this->date1)));
            $end     = date('Y-m-d',strtotime(str_replace('-','/',$this->date2)));        
            $orders =Order::whereBetween('order_date', [$start, $end])->orderBy('created_at', 'DESC')->get();
        }else{
            $orders= Order::orderBy('created_at', 'DESC')->get();
        }
        
        return view('common.export.order-wize.order', ['orders'=>$orders]);
    }


    public function registerEvents(): array
    {
        return [
            // Array callable, refering to a static method.
            AfterSheet::class => function(afterSheet $event){
                $event->sheet->getStyle('A1:X1')->applyFromArray([
                    'font' =>[
                        'bold'=>true,
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_GRADIENT_LINEAR,
                        'rotation' => 90,
                        'startColor' => [
                            'argb' => 'fff',
                        ],
                        'endColor' => [
                            'argb' => 'ffc107',
                        ],
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['argb' => 'ffc107'],
                        ],
                    ],
                ]);
            }
                        
        ];
    }
    
}
