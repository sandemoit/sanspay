<x-app-layout>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Akun Diblokir</div>

                <div class="card-body">
                    <div class="alert alert-danger" role="alert">
                        Maaf, akun Anda telah diblokir/dinonaktifkan. Silakan hubungi <a
                            href="https://wa.me/{{ configWeb('whatsapp_url')->value }}">CS Sans Pay</a> untuk informasi
                        lebih lanjut.
                    </div>

                    <a href="{{ route('logout') }}" type="submit" class="btn btn-primary">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
