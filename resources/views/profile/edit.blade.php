<x-app-layout>

    @include('layouts.breadcrumbs')

    <div class="row">
        <div class="col-lg-6">
            <div class="card radius-10">
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                    @include('profile.partials.tukar-point')
                </div>
            </div>
        </div>
        <div class="col-6">
            <div class="card radius-10">
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                    @include('profile.partials.update-pin-form')
                </div>
            </div>
        </div>
    </div><!--end row-->

    @push('custom-js')
        <script>
            function copyToClipboard(element) {
                var $temp = $("<input>");
                $("body").append($temp);
                $temp.val($("#referral").val()).select();
                document.execCommand("copy");
                $temp.remove();
                alert("Copied to clipboard");
            }
        </script>
    @endpush
</x-app-layout>
