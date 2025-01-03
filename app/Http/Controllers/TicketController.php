<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function create()
    {
        $title = 'Buat Tiket';
        return view('users.tickets.create', compact('title'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|in:Deposit,Withdraw,Transfer,Order,Speed Up,Cancel,Refund,Other',
            'message' => 'required|string',
        ]);

        $ticket = new Ticket();
        $ticket->code = 'TCK-' . strtoupper(substr(md5(rand()), 0, 6));
        $ticket->user_id = Auth::id();
        $ticket->subject = $request->subject;
        $ticket->message = $request->message;
        $ticket->status = 'open';
        $ticket->type = 'initial';
        $ticket->save();

        return redirect()->route('ticket')->with('success', 'Tiket berhasil dibuat.');
    }

    public function index()
    {
        $tickets = Ticket::where('user_id', Auth::id())->whereNull('parent_id')->get();
        $title = 'Daftar Tiket';

        return view('users.tickets.index', compact('tickets', 'title'));
    }

    public function show($id)
    {
        $ticket = Ticket::where('user_id', Auth::id())->where('id', $id)->first();
        if (!$ticket) {
            return redirect()->route('ticket')->with('error', 'Tiket tidak ditemukan.');
        }

        $messages = Ticket::where('parent_id', $ticket->id)->orWhere('id', $ticket->id)->orderBy('created_at')->get();
        return view('users.tickets.show', compact('ticket', 'messages'));
    }

    public function reply(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $ticket = Ticket::where('user_id', Auth::id())->where('id', $id)->first();
        if (!$ticket) {
            return redirect()->route('ticket')->with('error', 'Tiket tidak ditemukan.');
        }

        if ($ticket->status == 'closed') {
            return redirect()->route('ticket.show', $id)->with('error', 'Tiket sudah ditutup dan tidak dapat dibalas.');
        }

        $message = new Ticket();
        $message->code = $ticket->code;
        $message->user_id = Auth::id();
        $message->parent_id = $ticket->id;
        $message->message = $request->message;
        $message->status = 'open';
        $message->type = 'reply';
        $message->save();

        return redirect()->route('ticket.show', $id)->with('success', 'Pesan berhasil dikirim.');
    }
}
