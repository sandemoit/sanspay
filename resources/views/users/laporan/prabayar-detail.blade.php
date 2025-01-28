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
                width: 140px;
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
                    {{ \Carbon\Carbon::parse($trx->created_at)->translatedFormat('d/m/Y H:i:s') }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Update</div>
                <div class="detail-value">:
                    {{ \Carbon\Carbon::parse($trx->updated_at)->translatedFormat('d/m/Y H:i:s') }}
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-label">Produk</div>
                <div class="detail-value">: {{ $trx->name }}</div>
            </div>

            <div class="detail-row">
                <div class="detail-label">No Pelanggan</div>
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
                <div class="detail-label">Ket/SN</div>
                <div class="detail-value">: {{ formatSN($trx->sn) }}</div>
            </div>
        </div>

        <div class="struk-footer">
            <p class="mb-0">Silahkan refresh 0 - 1 menit untuk melihat status <span
                    style="color: #28a745">sukses</span></p>
            <p class="mb-0">Silahkan simpan struk ini sebagai bukti transaksi</p>
        </div>
    </div>
</x-app-layout>
