<x-app-layout>


    <div class="row">
        <div class="col-xl-8 mx-auto">

            <div class="card">
                <div class="card-body">
                    <h6 class="mb-0 text-uppercase text-center">{{ __($title) }}</h6>
                    <hr>
                    @if ($status == '1')
                        <div class="text-center">
                            <h3>Yeay, Berhasil Mengajukan :)</h3>
                            <a href="https://ibb.co.com/ZNTQybw"><img
                                    src="https://i.ibb.co.com/R7HXLJM/Pngtree-business-team-success-concept-people-5335905.png"
                                    alt="Success" width="400" border="0"></a>
                            <div>
                                <p>Data Anda sedang di tinjau oleh Admin, mohon tunggu konfirmasi dari kami.</p>
                            </div>
                            <div>
                                <p>Jika ada pertanyaan silahkan hubungi <a
                                        href="https://api.whatsapp.com/send?phone={{ configWeb('whatsapp_url')->value }}&text=Halo%20min...">Admin</a>
                                </p>
                            </div>
                        </div>
                    @elseif ($status == '2')
                        <div class="text-center">
                            <h3>Hore, Kamu berhasil naik level :D</h3>
                            <a href="https://ibb.co.com/d6tYYnf"><img
                                    src="https://i.ibb.co.com/4M2rrBF/Pngtree-cartoon-character-success-material-3687115.png"
                                    width="400" border="0"></a>
                            <div>
                                <p>Selamat, Kamu berhasil naik level ke Mitra dan resmi bergabung sebagai Mitra.</p>
                            </div>
                            <div>
                                <p>Nikmati
                                    semua fitur dan kemudahan yang kami berikan. Serta promosi dan harga khusus.</p>
                            </div>
                        </div>
                    @elseif ($status == '3')
                        <div class="text-center">
                            <h3>Yahh, Maaf pengajuan kamu ditolak :(</h3>
                            <a href="https://ibb.co.com/XDwYjjQ"><img
                                    src="https://i.ibb.co.com/ZSZ2GGj/Pngtree-man-with-sadness-face-concept-5311454.png"
                                    width="400"border="0"></a>
                            <div>
                                <p>Mohon maaf data kamu belum sesuai atau belum terverifikasi.</p>
                                <p>Jangan berkecil hati, kamu dapat mencoba pengajuankembali dengan data yang sesuai,
                                    silahkan klik <a href="{{ route('upgrade.mitra') }}">Upgrade Mitra</a></p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
