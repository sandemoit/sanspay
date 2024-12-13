<x-app-layout>

    @include('layouts.breadcrumbs')

    <div class="row row-cols-1 row-cols-lg-2 row-cols-xxl-4">
        <div class="col mb-3">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-2">
                        <div>
                            <p class="mb-0 fs-6">Total Revenue</p>
                        </div>
                        <div class="ms-auto widget-icon-small text-white bg-gradient-purple">
                            <ion-icon name="wallet-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <div>
                            <h4 class="mb-0">$92,854</h4>
                        </div>
                        <div class="ms-auto">+6.32%</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col mb-3">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-2">
                        <div>
                            <p class="mb-0 fs-6">Total Customer</p>
                        </div>
                        <div class="ms-auto widget-icon-small text-white bg-gradient-info">
                            <ion-icon name="people-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <div>
                            <h4 class="mb-0">48,789</h4>
                        </div>
                        <div class="ms-auto">+12.45%</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col mb-3">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-2">
                        <div>
                            <p class="mb-0 fs-6">Total Orders</p>
                        </div>
                        <div class="ms-auto widget-icon-small text-white bg-gradient-danger">
                            <ion-icon name="bag-handle-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <div>
                            <h4 class="mb-0">88,234</h4>
                        </div>
                        <div class="ms-auto">+3.12%</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col mb-3">
            <div class="card radius-10">
                <div class="card-body">
                    <div class="d-flex align-items-start gap-2">
                        <div>
                            <p class="mb-0 fs-6">Conversion Rate</p>
                        </div>
                        <div class="ms-auto widget-icon-small text-white bg-gradient-success">
                            <ion-icon name="bar-chart-outline"></ion-icon>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mt-3">
                        <div>
                            <h4 class="mb-0">48.76%</h4>
                        </div>
                        <div class="ms-auto">+8.52%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end row-->
</x-app-layout>
