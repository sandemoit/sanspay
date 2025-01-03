<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $openTicketsCount = Ticket::whereNull('parent_id')->where('status', 'open')->count();
        $closedTicketsCount = Ticket::whereNull('parent_id')->where('status', 'closed')->count();
        $answerTicketsCount = Ticket::whereNull('parent_id')->where('status', 'answer')->count();

        return view('admin.ticket.index', compact('openTicketsCount', 'closedTicketsCount', 'answerTicketsCount'));
    }

    public function getData(Request $request)
    {
        $tickets = Ticket::whereNull('parent_id')->select(['id', 'code', 'user_id', 'subject', 'status', 'created_at']);

        return datatables()->of($tickets)
            ->addColumn('date', function ($row) {
                return tanggal($row->created_at);
            })
            ->addColumn('no', function ($row) {
                return $row->id;
            })
            ->addColumn('username', function ($row) {
                return $row->user->fullname;
            })
            ->addColumn('action', function ($row) {
                return '<a href="' . route('admin.ticket.detail', $row->id) . '" class="btn btn-sm btn-info">Detail</a>';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function detail($id)
    {
        $ticket = Ticket::where('id', $id)->first();
        if (!$ticket) {
            return redirect()->route('admin.ticket')->with('error', 'Tiket tidak ditemukan.');
        }

        $messages = Ticket::where('parent_id', $ticket->id)->orWhere('id', $ticket->id)->orderBy('created_at')->get();
        return view('admin.ticket.detail', compact('ticket', 'messages'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:open,closed,answer',
            'message' => 'nullable|string',
        ]);

        $ticket = Ticket::where('id', $id)->first();
        if (!$ticket) {
            return redirect()->route('admin.ticket')->with('error', 'Tiket tidak ditemukan.');
        }

        $ticket->status = $request->status;
        $ticket->save();

        if ($request->message) {
            $message = new Ticket();
            $message->code = $ticket->code;
            $message->user_id = Auth::id();
            $message->parent_id = $ticket->id;
            $message->message = $request->message;
            $message->status = 'open';
            $message->type = 'reply';
            $message->save();
        }

        return redirect()->route('admin.ticket.detail', $id)->with('success', 'Tiket berhasil diperbarui.');
    }
}
