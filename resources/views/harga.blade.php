@extends('layouts.guest')
@section('content')
    <section class="gj ir hj sp jr i pg ">
        <!-- Section Title Start -->
        <div x-data="{ sectionTitle: `Daftar Harga` }">
            <div class=" bb ze rj ki xn vq">
                <h2 x-text="sectionTitle" class="fk vj pr kk wm on/5 gq/2 bb _b">
                </h2>
            </div>

        </div>
        <!-- Section Title End -->
        @livewire('harga')
    </section>
@endsection
