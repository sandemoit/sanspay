<x-app-layout>
    @push('custom-css')
        <link href="{{ asset('/') }}plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    @endpush

    <div class="alert alert-primary" role="alert">
        <div class="text-left mb-2">
            <span class="badge bg-primary">🎉 Ayo ajak teman-teman gabung di Sans Pay!</span>
        </div>
        <div>
            Setiap teman yang kamu ajak dan transaksi pertama selesai, kamu akan dapat 2500 poin! ✨ Poin ini bisa
            langsung ditukar jadi Saldo Transaksi.
            Semakin banyak teman yang kamu ajak, semakin besar saldo kamu. Yuk, mulai sekarang! 🚀
        </div>
    </div>
    <hr />
    <div class="row row-cols-1 row-cols-lg-2 row-cols-xxl-3">
        <div class="col mb-3">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-2">
                        <div>
                            <p class="mb-0 fs-6">{{ __('Code Referral') }}</p>
                        </div>
                        <div class="ms-auto widget-icon-small text-white bg-gradient-success">
                            <ion-icon name="link-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <div>
                            <h4 class="mb-0">{{ $user->code_referral }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col mb-3">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-2">
                        <div>
                            <p class="mb-0 fs-6">{{ __('Total Downline') }}</p>
                        </div>
                        <div class="ms-auto widget-icon-small text-white bg-gradient-warning">
                            <ion-icon name="person-add-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <div>
                            <h4 class="mb-0">{{ $total_referral }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col mb-3">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-2">
                        <div>
                            <p class="mb-0 fs-6">{{ __('Total Point') }}</p>
                        </div>
                        <div class="ms-auto widget-icon-small text-white bg-gradient-danger">
                            <ion-icon name="close-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <div>
                            <h4 class="mb-0">{{ $total_point }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table id="referrals-table" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>{{ __('Date') }}</th>
                            <th>{{ __('Username From') }}</th>
                            <th>{{ __('Username To') }}</th>
                            <th>{{ __('Point') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody class="align-middle">
                        {{-- here to fetch data --}}
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('custom-js')
        <script src="{{ asset('/') }}plugins/datatable/js/jquery.dataTables.min.js"></script>
        <script src="{{ asset('/') }}plugins/datatable/js/dataTables.bootstrap5.min.js"></script>
        <script src="{{ asset('/') }}js/table-datatable.js"></script>
        <script type="text/javascript" src="{{ asset('/') }}js/referral.js"></script>
    @endpush
</x-app-layout>
