<x-app-layout>
    @push('custom-css')
        <style>
            .struk-container {
                max-width: 400px;
                margin: 20px auto;
                background: white;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                padding: 20px;
            }

            .struk-header {
                text-align: center;
                margin-bottom: 20px;
                padding-bottom: 15px;
                border-bottom: 1px dashed #ddd;
            }

            .struk-detail {
                margin-bottom: 20px;
                color: black
            }

            .detail-row {
                display: flex;
                margin-bottom: 10px;
            }

            .detail-label {
                width: 100px;
            }

            .detail-value {
                flex: 1;
                padding-left: 10px;
            }

            .struk-footer {
                text-align: center;
                margin-top: 20px;
                padding-top: 15px;
                border-top: 1px dashed #ddd;
                font-size: 12px;
                color: #666;
            }
        </style>
    @endpush

    <div class="struk-container">
        <div class="struk-header">
            <h4>Detail Transaksi</h4>
            <div>ID Order: {{ $trx->id_order }}</div>
        </div>

        <div class="struk-detail">
            <div class="detail-row">
                <div class="detail-label">Tanggal</div>
                <div class="detail-value">:
                    {{ \Carbon\Carbon::parse($trx->created_at)->translatedFormat('d F Y H:i:s') }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Update</div>
                <div class="detail-value">:
                    {{ \Carbon\Carbon::parse($trx->updated_at)->translatedFormat('d F Y H:i:s') }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Produk</div>
                <div class="detail-value">: {{ $trx->name }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Nomor</div>
                <div class="detail-value">: {{ $trx->data }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Status</div>
                <div class="detail-value">:
                    @if ($trx->status == 'Sukses')
                        <span style="color: #28a745">{{ $trx->status }}</span>
                    @else
                        <span style="color: #dc3545">{{ $trx->status }}</span>
                    @endif
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">SN</div>
                <div class="detail-value">:
                    @php
                        if ($trx->sn) {
                            // Jika dimulai dengan DNID, ambil bagian pertama sebelum slash
                            if (str_starts_with($trx->sn, 'DNID')) {
                                $parts = explode('/', $trx->sn);
                                echo $parts[0];
                            }
                            // Untuk token PLN, ambil sampai slash kedua
                            else {
                                $parts = explode('/', $trx->sn);
                                if (count($parts) >= 2) {
                                    echo $parts[0] . '/' . $parts[1];
                                } else {
                                    echo $trx->sn;
                                }
                            }
                        } else {
                            echo '-';
                        }
                    @endphp
                </div>
            </div>
        </div>

        <div class="struk-footer">
            Terima kasih telah bertransaksi
        </div>
    </div>
</x-app-layout>
