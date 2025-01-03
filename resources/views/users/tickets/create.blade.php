<x-app-layout>
    <div class="row">
        <div class="col-xl-8 mx-auto">
            <div class="card p-2">
                <div class="card-body">
                    <h4 class="text-uppercase">{{ $title }}</h4>
                    <form action="{{ route('ticket.store') }}" method="POST" class="row">
                        @csrf
                        <div class="form-group">
                            <label for="subject" class="form-label">Subjek</label>
                            <select name="subject" id="subject"
                                class="form-control @error('subject') is-invalid @enderror">
                                <option value="Deposit">Deposit</option>
                                <option value="Withdraw">Withdraw</option>
                                <option value="Transfer">Transfer</option>
                                <option value="Order">Order</option>
                                <option value="Speed Up">Speed Up</option>
                                <option value="Cancel">Cancel</option>
                                <option value="Refund">Refund</option>
                                <option value="Other">Other</option>
                            </select>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group mb-3">
                            <label for="message" class="form-label">Pesan</label>
                            <textarea name="message" id="message" class="form-control @error('message') is-invalid @enderror">{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-6">
                            <div class="d-grid">
                                <a href="{{ route('ticket') }}" class="btn btn-danger">{{ __('Back') }}</a>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">{{ __('Submit Ticket') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
