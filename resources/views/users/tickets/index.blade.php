<x-app-layout>
    <div class="row">
        <div class="col-xl-8 mx-auto">
            <div class="card p-2">
                <div class="card-body">
                    <h4 class="text-uppercase">{{ $title }}</h4>
                    <a href="{{ route('ticket.create') }}" class="btn btn-primary mb-3">Buat Tiket Baru</a>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode Tiket</th>
                                <th>Subjek</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($tickets as $index => $ticket)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $ticket->code }}</td>
                                    <td>{{ $ticket->subject }}</td>
                                    <td>
                                        @switch($ticket->status)
                                            @case('open')
                                                <span class="badge rounded-pill bg-primary">{{ $ticket->status }}</span>
                                            @break

                                            @case('closed')
                                                <span class="badge rounded-pill bg-dark">{{ $ticket->status }}</span>
                                            @break

                                            @case('answer')
                                                <span class="badge rounded-pill bg-warning">{{ $ticket->status }}</span>
                                            @break

                                            @default
                                                <span class="badge rounded-pill bg-secondary">{{ $ticket->status }}</span>
                                        @endswitch
                                    </td>
                                    <td>
                                        <a href="{{ route('ticket.show', $ticket->id) }}"
                                            class="btn btn-sm btn-info"><ion-icon name="eye-outline"></ion-icon>
                                            Lihat</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
