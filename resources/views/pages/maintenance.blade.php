<x-app-layout>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Maintenance</div>

                <div class="card-body">
                    <div class="alert alert-danger" role="alert">
                        Maaf, website sedang dalam maintenance. Silakan kembali lagi nanti.
                    </div>

                    <a href="{{ route('logout') }}" type="submit" class="btn btn-primary">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
