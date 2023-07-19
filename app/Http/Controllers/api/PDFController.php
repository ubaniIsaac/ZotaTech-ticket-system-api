<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class PDFController extends Controller
{
    public function index()
    {
        $data =[
            'name'=> 'Isaac',
            'event' => 'BECON',
            'day' => '2023-06-18',
            'quantity' => 4,
            'id' => '123efb9808p',
            'eventOwner'=>'EGFM'
        ];
        $pdf = Pdf::loadView('index', $data);
        return $pdf->download('ticket.pdf');
       
    }
}
