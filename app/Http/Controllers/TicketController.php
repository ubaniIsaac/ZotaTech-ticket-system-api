<?php

namespace App\Http\Controllers;

use App\Http\Requests\TicketRequest;
use App\Http\Resources\TicketCollection;
use App\Http\Resources\TicketResource;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TicketController extends Controller
{
    public function index()
    {
        return TicketCollection::collection(Ticket::paginate(10));
        //$tickets = Ticket::all();

        //return response()->json(['data' => $tickets]);
    }

    public function store(TicketRequest $request): JsonResponse
    {

        $ticket = Ticket::create($request->validated());

        return response()->json([
            'data' => new TicketResource($ticket)
        ], Response::HTTP_CREATED);
    }

    public function show(Ticket $ticket):JsonResponse
    {
        try{
            $ticket = Ticket::findOrFail($ticket);

            return response()->json([
                'Message' => 'Ticket Found',
                'data' => new TicketResource($ticket),
            ],Response::HTTP_OK);
        }catch(\Throwable $th){
            return response()->json([
                'Message'=> 'Ticket not Found',
            ], Response::HTTP_NOT_FOUND);
        }
        
    }

    public function update(Request $request, Ticket $ticket):JsonResponse
    {
        try{
            $ticket=Ticket::findOrFail($ticket);

            $ticket->update($request->all());

            return response()->json([
                'data' =>new TicketResource($ticket)
            ], Response::HTTP_OK);

        }catch(\Throwable $th){
            return response()->json([
                'data'=>'Ticket not Found'
            ], Response::HTTP_NOT_FOUND);
        }
        

        
    }

    public function destroy(Ticket $ticket)
    {
        try{
            $ticket = Ticket::findOrFail($ticket);

            $ticket->delete();
            return response()->json([
                'Message' => 'Ticket deleted successfully'
            ], Response::HTTP_OK);
        }catch(\Throwable $th){
            return response()->json([
                'Message' => 'Ticket not Found'
            ], Response::HTTP_NOT_FOUND);
        }
       
    }
}
