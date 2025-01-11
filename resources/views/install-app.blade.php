@push('custom-css')
    <style>
        #installApp img {
            width: 20px;
            position: relative;
            text-align: center;
            align-self: center;
            margin-right: 10px;
            filter: brightness(0) saturate(100%) invert(100%) sepia(0%) saturate(7487%) hue-rotate(223deg) brightness(101%) contrast(104%);
        }
    </style>
@endpush
<x-guest-layout :title="$title">
    <section class="gj ir hj sp jr i pg ">
        <!-- Section Title Start -->
        <div x-data="{ sectionTitle: `Cara Instal Aplikasi Sans Pay di Android & iOS` }">
            <div class="bb ze rj ki xn vq">
                <h2 x-text="sectionTitle" class="fk vj pr kk wm on/5 gq/2 bb _b">
                </h2>
            </div>
        </div>
        <!-- Section Title End -->

        <div class="bb ze ki xn 2xl:ud-px-0">
            <!-- Android Section -->
            <div class="mb-5">
                <h3 class="text-lg font-semibold mb-2">Untuk Android</h3>
                <p class="mb-0">
                    Klik tombol "Install Aplikasi" di bawah untuk menginstal
                    aplikasi Sans Pay di perangkat Android
                    Anda.
                </p>
                <button type="button" class="ek jk lk gh gi hi rg ml il vc _d _l" id="installApp">
                    <img src="{{ asset('/') }}web/images/android-brands-solid.svg" alt="Icon" />
                    Install Aplikasi</button>
            </div>

            <!-- iOS Section -->
            <div class="mb-10">
                <h3 class="text-lg font-bold mb-2">Untuk iOS</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="row">
                        <div class="col-sm-12 col-lg-4 mb-3">
                            <!-- Step 1 -->
                            <div class="items-start">
                                <h4 class="text-base font-medium mb-2">1. Buka browser kalian dan buka halaman
                                    sanspay.id, lalu pilih panah atas.
                                </h4>
                                <img src="{{ asset('storage/images/campur/step-ios-1.png') }}"
                                    alt="Buka browser kalian, lalu pilih panah atas" class="w-full">
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-4 mb-3">
                            <!-- Step 2 -->
                            <div class="items-start">
                                <h4 class="text-base font-medium mb-2">2. Pilih Tambah Layar ke Utama.</h4>
                                <img src="{{ asset('storage/images/campur/step-ios-2.png') }}"
                                    alt="Pilih Tambah Layar ke Utama" class="w-full">
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-4">
                            <!-- Step 3 -->
                            <div class="flex flex-col items-start">
                                <h4 class="text-base font-medium mb-2">3. Aplikasi Sans Pay siap bertransaksi!</h4>
                                <img src="{{ asset('storage/images/campur/step-ios-3.png') }}"
                                    alt="Aplikasi Sans Pay siap bertransaksi" class="w-full">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @push('custom-js')
        <script>
            let deferredPrompt;

            window.addEventListener('beforeinstallprompt', (e) => {
                deferredPrompt = e;
            });

            const installApp = document.getElementById('installApp');
            installApp.addEventListener('click', async () => {
                if (deferredPrompt !== null) {
                    deferredPrompt.prompt();
                    const {
                        outcome
                    } = await deferredPrompt.userChoice;
                    if (outcome === 'accepted') {
                        deferredPrompt = null;
                    }
                }
            });
        </script>
    @endpush
</x-guest-layout>
