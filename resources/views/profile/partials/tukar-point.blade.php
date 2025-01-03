<h5 class="mb-0 mt-4">{{ __('Penukaran Point') }}</h5>
<p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
    {{ __('Masukan nominal point yang ingin di tukar dan masukan kata sandi Anda.') }}
    <i>Point saat ini: {{ $user->point }}</i>
</p>
<hr>

<form method="POST" action="{{ route('profile.tukarPoint') }}">
    @csrf
    <div class="mb-3">
        <label class="form-label">{{ __('Nominal Point') }}</label>
        <input type="text" class="form-control" name="point" id="point" required autocomplete="off">
    </div>
    <div class="mb-3">
        <label class="form-label">{{ __('PIN Transaksi') }}</label>
        <input type="password" class="form-control" name="pin" id="pin" required autocomplete="off">
    </div>
    <div class="d-flex flex items-center gap-4">
        <x-primary-button>{{ __('Save') }}</x-primary-button>
    </div>
</form>
