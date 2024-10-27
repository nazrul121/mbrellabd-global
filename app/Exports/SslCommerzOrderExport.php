<?php

namespace App\Exports;

use App\Models\Order;
use App\Models\Order_payment;
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

class SslCommerzOrderExport implements FromView, ShouldAutoSize,  Responsable, WithEvents
{
    use Exportable;

    public $status; 
    // public $date2;

    public function __construct($status){
        $this->status = $status;
        // $this->date2 = $date2;
    }


    public function view():view
    {
        if($this->status !=''){
            $orderPayments = Order_payment::where('payment_type_id','!=','2')->where('status',$this->status)->orderBy('id','DESC')->get();
        }else{
            $orderPayments = Order_payment::where('payment_type_id','!=','2')->orderBy('id','DESC')->get();
        }
        
        return view('common.export.sslcommerz-order', ['orderPayments'=>$orderPayments]);
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
