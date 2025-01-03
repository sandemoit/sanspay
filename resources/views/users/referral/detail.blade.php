<x-app-layout>
    <div class="col-xl-8 mx-auto">
        <div class="border p-3 rounded">
            <h4 class="text-uppercase">Detail Referral</h4>
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Username From: {{ $referral->username_from }}</h5>
                    <p class="card-text"><strong>Username To:</strong> {{ $referral->username_to }}</p>
                    <p class="card-text"><strong>Point:</strong> {{ $referral->point }}</p>
                    <p class="card-text"><strong>Status:</strong> <span
                            class="badge bg-{{ $referral->status == 'active' ? 'success' : 'danger' }}">{{ ucfirst($referral->status) }}</span>
                    </p>
                    <p class="card-text"><strong>Tanggal Dibuat:</strong>
                        {{ $referral->created_at->format('d M Y H:i:s') }}
                    </p>
                </div>
            </div>
            <a href="{{ route('program.referral') }}" class="btn btn-secondary mt-3">Kembali</a>
        </div>
</x-app-layout>
