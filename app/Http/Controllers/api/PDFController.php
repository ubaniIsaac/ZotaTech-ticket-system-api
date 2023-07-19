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
            'date' => '2023-06-18',
            'time' => '10:00',
            'quantity' => 4,
            'id' => '01h5q2h47tdggybqs8jggq4hv8',
            'eventOwner'=>'EGFM',
            'location'=>'Faith Plaza Bariga',
            'link' => 'http://127.0.0.1:8000/api/v1/tickets/01h5q2h47tdggybqs8jggq4hv8',
        ];
        $pdf = Pdf::loadView('index', $data);
        return $pdf->download('ticket.pdf');
       
    }
}
