@push('custom-css')
    <style>
        .chat-container {
            max-height: 400px;
            overflow-y: auto;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
        }

        .chat-message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            max-width: 70%;
        }

        .user-message {
            background-color: #f1f1f1;
            text-align: right;
            margin-left: 30%;
        }

        .admin-message {
            background-color: #e1ffc7;
            text-align: left;
        }

        .chat-message strong {
            display: block;
            margin-bottom: 5px;
        }

        .chat-message small {
            display: block;
            font-size: 0.9em;
            color: #888;
        }
    </style>
@endpush
<x-app-layout>
    <div class="col-xl-8 mx-auto">
        <div class="border p-3 rounded">
            <h4 class="text-uppercase">Detail Tiket</h4>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Kode Tiket: {{ $ticket->code }}</h5>
                    <p class="card-text"><strong>Subjek:</strong> {{ $ticket->subject }}</p>
                    <p class="card-text"><strong>Status:</strong> {{ $ticket->status }}</p>
                    <p class="card-text"><strong>Tanggal Dibuat:</strong>
                        {{ $ticket->created_at->format('d M Y H:i:s') }}</p>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-body">
                    <h5 class="card-title">Pesan</h5>
                    <div class="chat-container">
                        @foreach ($messages as $message)
                            <div
                                class="chat-message {{ $message->user_id == Auth::id() ? 'user-message' : 'admin-message' }}">
                                <strong>{{ $message->user->fullname }}</strong>
                                {{ $message->message }}
                                <small class="text-muted">{{ $message->created_at->format('d M Y H:i:s') }}</small>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @if ($ticket->status != 'closed')
                <form action="{{ route('ticket.reply', $ticket->id) }}" method="POST" class="mt-3">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="message" class="form-label">Pesan</label>
                        <textarea name="message" id="message" class="form-control @error('message') is-invalid @enderror" rows="4"></textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim Pesan</button>
                </form>
            @else
                <div class="alert alert-warning mt-3">
                    Tiket sudah ditutup dan tidak dapat dibalas.
                </div>
            @endif
            <a href="{{ route('ticket') }}" class="btn btn-secondary mt-3">Kembali</a>
        </div>
    </div>
</x-app-layout>
