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
    <section class="gj do ir hj sp jr i pg" id="home">
        <!-- Hero Images -->
        <div class="xc fn zd/2 2xl:ud-w-187.5 bd 2xl:ud-h-171.5 h q r">
            <img src="{{ asset('/') }}web/images/shape-01.svg" alt="shape"
                class="xc 2xl:ud-block h t -ud-left-[10%] ua" />
            <img src="{{ asset('/') }}web/images/shape-02.svg" alt="shape" class="xc 2xl:ud-block h u p va" />
            <img src="{{ asset('/') }}web/images/shape-03.svg" alt="shape" class="xc 2xl:ud-block h v w va" />
            <img src="{{ asset('/') }}web/images/shape-04.svg" alt="shape" class="h q r" />
            <img src="{{ asset('/') }}web/images/hero.webp" alt="Mitra Sans Pay" class="h q r ua"
                style="top: 4rem;" />
        </div>

        <!-- Hero Content -->
        <div class="bb ze ki xn 2xl:ud-px-0">
            <div class="tc _o">
                <div class=" jn/2">
                    <h1 class="fk vj zp or kk wm wb">Selamat Datang di Sans Pay!</h1>
                    <p class="fq">
                        Kami adalah solusi terpercaya untuk kebutuhan pulsa dan PPOB Anda. Dengan layanan yang lengkap,
                        murah, cepat, dan terjamin, kami siap membantu Anda dalam setiap transaksi.
                    </p>

                    <div class="tc tf yo mb" style="gap: 0.5rem">
                        <a href="{{ route('login') }}" class="ek jk lk gh gi hi rg ml il vc _d _l">Gabung dan
                            Nikmati!</a>
                        <a href="{{ route('instal-app') }}" class="ek jk lk gh gi hi rg ml il vc _d _l" id="installApp">
                            <img src="{{ asset('/') }}web/images/android-brands-solid.svg" alt="Icon" />
                            <img src="{{ asset('/') }}web/images/apple-brands-solid.svg" alt="Icon" />
                            Install Aplikasi</a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ===== Hero End ===== -->

    <!-- ===== Small Features Start ===== -->
    <section id="features">
        <div class="bb ze ki yn 2xl:ud-px-12.5">
            <div class="tc uf zo xf ap zf bp mq">
                <!-- Small Features Item -->
                <div class=" step_start kn to/3 tc cg oq">
                    <div class="tc wf xf cf ae cd rg mh">
                        <img src="{{ asset('/') }}web/images/right-to-bracket-solid.svg" alt="Icon" />
                    </div>
                    <div>
                        <h4 class="ek yj go kk wm xb">Daftar/Login</h4>
                        <p>Pendaftaran gratis dan tidak ribet, silahkan akses ke menu Gaabung. Kemudian Masuk
                            Akun.</p>
                    </div>
                </div>

                <!-- Small Features Item -->
                <div class=" step_start kn to/3 tc cg oq">
                    <div class="tc wf xf cf ae cd rg nh">
                        <img src="{{ asset('/') }}web/images/wallet-solid.svg" alt="Icon" />
                    </div>
                    <div>
                        <h4 class="ek yj go kk wm xb">Deposit Saldo</h4>
                        <p>Metode deposit lengkap, tersedia deposit melalui Bank, E-Money, Pulsa Transfer, dan QRIS.</p>
                    </div>
                </div>

                <!-- Small Features Item -->
                <div class=" step_start kn to/3 tc cg oq">
                    <div class="tc wf xf cf ae cd rg oh">
                        <img src="{{ asset('/') }}web/images/cart-plus-solid.svg" alt="Icon" />
                    </div>
                    <div>
                        <h4 class="ek yj go kk wm xb">Mulai Tansaksi</h4>
                        <p>Setelah akun Anda memiliki saldo, Anda dapat melakukan Transaksi yang Anda inginkan.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ===== Small Features End ===== -->

    <!-- ===== Services Start ===== -->
    <section class="i pg ji gp uq" id="#features">
        <!-- Bg Shapes -->

        <!-- Section Title Start -->
        <div x-data="{ sectionTitle: `Mengapa Memilih SansP ay?`, sectionTitleText: `Kami berkomitmen untuk memberikan layanan terbaik dengan fitur yang dirancang khusus untuk memenuhi kebutuhan Anda. Temukan berbagai keunggulan yang membuat Sans Pay menjadi pilihan utama untuk pulsa dan PPOB.` }">
            <div class=" bb ze rj ki xn vq">
                <h2 x-text="sectionTitle" class="fk vj pr kk wm on/5 gq/2 bb _b">
                </h2>
                <p class="bb on/5 wo/5 hq" x-text="sectionTitleText"></p>
            </div>
        </div>
        <!-- Section Title End -->

        <div class="bb ze ki xn yq mb en">
            <div class="wc qf pn xo ng">
                <!-- Service Item -->
                <div class=" keunggulan sg oi pi zq ml il am cn _m">
                    <img src="{{ asset('/') }}web/images/icon-01.svg" alt="Icon" />
                    <h4 class="ek zj kk wm nb _b">Layanan 24/7</h4>
                    <p>Dukungan pelanggan yang siap membantu kapan saja, memastikan setiap transaksi berjalan lancar.
                    </p>
                </div>

                <!-- Service Item -->
                <div class=" keunggulan sg oi pi zq ml il am cn _m">
                    <img src="{{ asset('/') }}web/images/cash-register-solid.svg" alt="Icon" />
                    <h4 class="ek zj kk wm nb _b">Transaksi Otomatis</h4>
                    <p>Memudahkan pelanggan dengan sistem transaksi otomatis yang cepat dan efisien.</p>
                </div>

                <!-- Service Item -->
                <div class=" keunggulan sg oi pi zq ml il am cn _m">
                    <img src="{{ asset('/') }}web/images/wallet-solid.svg" alt="Icon" />
                    <h4 class="ek zj kk wm nb _b">Deposit Mudah</h4>
                    <p>Proses deposit yang sederhana dan cepat, memudahkan pengguna untuk menambah saldo.</p>
                </div>

                <!-- Service Item -->
                <div class=" keunggulan sg oi pi zq ml il am cn _m">
                    <img src="{{ asset('/') }}web/images/user-shield-solid.svg" alt="Icon" />
                    <h4 class="ek zj kk wm nb _b">Keamanan Terjamin</h4>
                    <p>Sistem keamanan yang kuat untuk melindungi data dan transaksi pelanggan.</p>
                </div>

                <!-- Service Item -->
                <div class=" keunggulan sg oi pi zq ml il am cn _m">
                    <img src="{{ asset('/') }}web/images/boxes-stacked-solid.svg" alt="Icon" />
                    <h4 class="ek zj kk wm nb _b">Beragam Pilihan Produk</h4>
                    <p>Menyediakan berbagai jenis pulsa dan layanan PPOB, memenuhi semua kebutuhan pelanggan.</p>
                </div>

                <!-- Service Item -->
                <div class=" keunggulan sg oi pi zq ml il am cn _m">
                    <img src="{{ asset('/') }}web/images/arrow-up-right-dots-solid.svg" alt="Icon" />
                    <h4 class="ek zj kk wm nb _b">Inovasi Berkelanjutan</h4>
                    <p>Selalu berusaha untuk meningkatkan layanan dan fitur, mengikuti perkembangan teknologi dan
                        kebutuhan pasar.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- ===== Services End ===== -->


    <!-- ===== Testimonials Start ===== -->
    <section class="hj rp hr" id="testimoni">
        <!-- Section Title Start -->
        <div x-data="{ sectionTitle: `Testimoni Pelanggan`, sectionTitleText: `Kami sangat bangga atas kepercayaan pelanggan terhadap layanan kami. Berikut beberapa komentar yang memuaskan dari pelanggan.` }">
            <div class=" bb ze rj ki xn vq">
                <h2 x-text="sectionTitle" class="fk vj pr kk wm on/5 gq/2 bb _b">
                </h2>
                <p class="bb on/5 wo/5 hq" x-text="sectionTitleText"></p>
            </div>


        </div>
        <!-- Section Title End -->

        <div class="bb ze ki xn ar">
            <div class=" jb cq">
                <!-- Slider main container -->
                <div class="swiper testimonial-01">
                    <!-- Additional required wrapper -->
                    <div class="swiper-wrapper">
                        <!-- Slides -->
                        <div class="swiper-slide">
                            <div class="i hh rm sg vk xm bi qj">
                                <!-- Border Shape -->
                                <span class="rc je md/2 gh xg h q r"></span>
                                <span class="rc je md/2 mh yg h q p"></span>

                                <div class="tc sf rn tn un zf dp">
                                    <img class="bf" src="{{ asset('/') }}web/images/testimonial.svg"
                                        alt="User" />

                                    <div>
                                        <p class="ek ik xj _p kc fb">
                                            Saya sangat puas menggunakan aplikasi ini selama bertahun-tahun. Pertanyaan
                                            saya langsung ditanggapi oleh CS dan saya pernah menerima bonus mingguan
                                            sebesar Rp 50.000. Lumayan, keuntungan penjualan ditambah bonus membuat
                                            pendapatan saya meningkat. Terima kasih Sans Pay.
                                        </p>

                                        <div class="tc yf vf">
                                            <div>
                                                <span class="rc ek xj kk wm zb">Putra Ardila</span>
                                                <span class="rc">Jambi, Muara Bungo</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="i hh rm sg vk xm bi qj">
                                <!-- Border Shape -->
                                <span class="rc je md/2 gh xg h q r"></span>
                                <span class="rc je md/2 mh yg h q p"></span>

                                <div class="tc sf rn tn un zf dp">
                                    <img class="bf" src="{{ asset('/') }}web/images/testimonial.png"
                                        alt="User" />

                                    <div>
                                        <p class="ek ik xj _p kc fb">
                                            Saya sangat bersyukur bergabung dengan Sans Pay, karena transaksi saya
                                            berjalan lancar dan aman. Saya juga sangat terbantu dengan bonus yang
                                            diberikan, itu sangat membantu perekonomian keluarga saya. Terima
                                            kasih Sans Pay! 😊🙏
                                        </p>

                                        <div class="tc yf vf">
                                            <div>
                                                <span class="rc ek xj kk wm zb">Arya Fadilah</span>
                                                <span class="rc">Jawa Barat</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <div class="i hh rm sg vk xm bi qj">
                                <!-- Border Shape -->
                                <span class="rc je md/2 gh xg h q r"></span>
                                <span class="rc je md/2 mh yg h q p"></span>

                                <div class="tc sf rn tn un zf dp">
                                    <img class="bf" src="{{ asset('/') }}web/images/testimonial.png"
                                        alt="User" />

                                    <div>
                                        <p class="ek ik xj _p kc fb">
                                            Saya sangat bersyukur telah menggunakan Sans Pay selama 2 tahun ini,
                                            transaksi saya berjalan lancar
                                            trus,harga murah,CS fast respon
                                        </p>

                                        <div>
                                            <span class="rc ek xj kk wm zb">Rudi Hartono</span>
                                            <span class="rc">Bandung</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- If we need navigation -->
                    <div class="tc wf xf fg jb">
                        <div class="swiper-button-prev c tc wf xf ie ld rg _g dh pf ml vr hh rm tl zm rl ym">
                            <svg class="th lm" width="14" height="14" viewBox="0 0 14 14" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M3.52366 7.83336L7.99366 12.3034L6.81533 13.4817L0.333663 7.00002L6.81533 0.518357L7.99366 1.69669L3.52366 6.16669L13.667 6.16669L13.667 7.83336L3.52366 7.83336Z"
                                    fill="" />
                            </svg>
                        </div>
                        <div class="swiper-button-next c tc wf xf ie ld rg _g dh pf ml vr hh rm tl zm rl ym">
                            <svg class="th lm" width="14" height="14" viewBox="0 0 14 14" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.4763 6.16664L6.00634 1.69664L7.18467 0.518311L13.6663 6.99998L7.18467 13.4816L6.00634 12.3033L10.4763 7.83331H0.333008V6.16664H10.4763Z"
                                    fill="" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- ===== Testimonials End ===== -->

    <!-- ===== Counter Start ===== -->
    <section class="i pg qh rm ji hp">
        <img src="{{ asset('/') }}web/images/shape-11.svg" alt="Shape" class="of h ga ha ke" />
        <img src="{{ asset('/') }}web/images/shape-07.svg" alt="Shape" class="h ia o ae jf" />
        <img src="{{ asset('/') }}web/images/shape-14.svg" alt="Shape" class="h ja ka" />
        <img src="{{ asset('/') }}web/images/shape-15.svg" alt="Shape" class="h q p" />

        <div class="bb ze i va ki xn br">
            <div class="tc uf sn tn xf un gg">
                <div class=" me/5 ln rj">
                    <h2 class="gk vj zp or kk wm hc">1785</h2>
                    <p class="ek bk aq">Pengguna</p>
                </div>
                <div class=" me/5 ln rj">
                    <h2 class="gk vj zp or kk wm hc">533</h2>
                    <p class="ek bk aq">Layanan</p>
                </div>
                <div class=" me/5 ln rj">
                    <h2 class="gk vj zp or kk wm hc">2865</h2>
                    <p class="ek bk aq">Transaksi Sukses</p>
                </div>
                <div class=" me/5 ln rj">
                    <h2 class="gk vj zp or kk wm hc">346</h2>
                    <p class="ek bk aq">Happy Clients</p>
                </div>
            </div>
        </div>
    </section>
    <!-- ===== Counter End ===== -->

    <!-- ===== Clients Start ===== -->
    <section class="pj vp mr" id="product">
        <!-- Section Title Start -->
        <div x-data="{ sectionTitle: `Produk yang kami sediakan`, sectionTitleText: `Kami menawarkan beragam produk yang dirancang untuk memenuhi kebutuhan Anda.` }">
            <div class=" bb ze rj ki xn vq">
                <h2 x-text="sectionTitle" class="fk vj pr kk wm on/5 gq/2 bb _b">
                </h2>
                <p class="bb on/5 wo/5 hq" x-text="sectionTitleText"></p>
            </div>


        </div>
        <!-- Section Title End -->

        <div class="bb ze ah ch pm hj xp bc">
            <div class="wc rf qn xf wf">
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand1.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand2.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand3.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand4.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand5.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand6.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand7.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand8.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand9.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand10.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand11.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand12.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand13.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand14.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand15.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand16.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand17.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand18.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand19.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand20.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand21.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand22.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand23.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand24.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand25.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand26.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand27.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand28.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand29.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand30.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand31.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand32.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand33.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand34.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand35.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand36.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand37.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand38.png"
                        alt="Clients" />
                </a>
                <a href="javascript:;" class="rc ">
                    <img class="th wl ml il zl om" src="{{ asset('/') }}web/images/brand/brand39.png"
                        alt="Clients" />
                </a>
            </div>
        </div>
    </section>
    <!-- ===== Clients End ===== -->

    <!-- ===== Blog Start ===== -->
    {{-- <section class="ji gp uq" id="news">
        <!-- Section Title Start -->
        <div x-data="{ sectionTitle: `Latest Blogs & News`, sectionTitleText: `It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using.` }">
            <div class=" bb ze rj ki xn vq">
                <h2 x-text="sectionTitle" class="fk vj pr kk wm on/5 gq/2 bb _b">
                </h2>
                <p class="bb on/5 wo/5 hq" x-text="sectionTitleText"></p>
            </div>


        </div>
        <!-- Section Title End -->

        <div class="bb ye ki xn vq jb jo">
            <div class="wc qf pn xo zf iq">
                <!-- Blog Item -->
                <div class=" sg vk rm xm">
                    <div class="c rc i z-1 pg">
                        <img class="w-full" src="{{ asset('/') }}web/images/blog-01.png" alt="Blog" />

                        <div class="im h r s df vd yc wg tc wf xf al hh/20 nl il z-10">
                            <a href="javascript:;" class="vc ek rg lk gh sl ml il gi hi">Read More</a>
                        </div>
                    </div>

                    <div class="yh">
                        <div class="tc uf wf ag jq">
                            <div class="tc wf ag">
                                <img src="{{ asset('/') }}web/images/icon-man.svg" alt="User" />
                                <p>Musharof Chy</p>
                            </div>
                            <div class="tc wf ag">
                                <img src="{{ asset('/') }}web/images/icon-calender.svg" alt="Calender" />
                                <p>25 Dec, 2025</p>
                            </div>
                        </div>
                        <h4 class="ek tj ml il kk wm xl eq lb">
                            <a href="javascript:;">Free advertising for your online business</a>
                        </h4>
                    </div>
                </div>

                <!-- Blog Item -->
                <div class=" sg vk rm xm">
                    <div class="c rc i z-1 pg">
                        <img class="w-full" src="{{ asset('/') }}web/images/blog-02.png" alt="Blog" />

                        <div class="im h r s df vd yc wg tc wf xf al hh/20 nl il z-10">
                            <a href="javascript:;" class="vc ek rg lk gh sl ml il gi hi">Read More</a>
                        </div>
                    </div>

                    <div class="yh">
                        <div class="tc uf wf ag jq">
                            <div class="tc wf ag">
                                <img src="{{ asset('/') }}web/images/icon-man.svg" alt="User" />
                                <p>Musharof Chy</p>
                            </div>
                            <div class="tc wf ag">
                                <img src="{{ asset('/') }}web/images/icon-calender.svg" alt="Calender" />
                                <p>25 Dec, 2025</p>
                            </div>
                        </div>
                        <h4 class="ek tj ml il kk wm xl eq lb">
                            <a href="javascript:;">9 simple ways to improve your design skills</a>
                        </h4>
                    </div>
                </div>

                <!-- Blog Item -->
                <div class=" sg vk rm xm">
                    <div class="c rc i z-1 pg">
                        <img class="w-full" src="{{ asset('/') }}web/images/blog-03.png" alt="Blog" />

                        <div class="im h r s df vd yc wg tc wf xf al hh/20 nl il z-10">
                            <a href="javascript:;" class="vc ek rg lk gh sl ml il gi hi">Read More</a>
                        </div>
                    </div>

                    <div class="yh">
                        <div class="tc uf wf ag jq">
                            <div class="tc wf ag">
                                <img src="{{ asset('/') }}web/images/icon-man.svg" alt="User" />
                                <p>Musharof Chy</p>
                            </div>
                            <div class="tc wf ag">
                                <img src="{{ asset('/') }}web/images/icon-calender.svg" alt="Calender" />
                                <p>25 Dec, 2025</p>
                            </div>
                        </div>
                        <h4 class="ek tj ml il kk wm xl eq lb">
                            <a href="javascript:;">Tips to quickly improve your coding speed.</a>
                        </h4>
                    </div>
                </div>
            </div>
        </div>
    </section> --}}
    <!-- ===== Blog End ===== -->


    <!-- ===== CTA Start ===== -->
    <section class="i pg gh ji">
        <!-- Bg Shape -->
        <img class="h p q" src="{{ asset('/') }}web/images/shape-16.svg" alt="Bg Shape" />

        <div class="bb ye i z-10 ki xn dr">
            <div class="tc uf sn tn un gg">
                <div class=" to/2">
                    <h2 class="fk vj zp pr lk ac">
                        Gabung bersama 1000+ mitra maju bersama Sans Pay
                    </h2>
                    <p class="lk">
                        Kami membantu Anda meningkatkan bisnis Anda melalui platform kami yang inovatif dan aman.
                        Bergabunglah dengan kami dan jadilah bagian dari komunitas yang terus berkembang.
                    </p>
                </div>
                <div class=" bf">
                    <a href="{{ route('register') }}" class="vc ek kk hh rg ol il cm gi hi">
                        Bergabung Sekarang
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-guest-layout>
