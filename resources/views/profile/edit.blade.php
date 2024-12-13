<x-app-layout>

    @include('layouts.breadcrumbs')

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card radius-10">
                <div class="card-body">
                    <form>
                        <h5 class="mb-3">{{ __('Edit Profile') }}</h5>
                        <div class="mb-4 d-flex flex-column gap-3 align-items-center justify-content-center">
                            <div class="user-change-photo shadow">
                                <img src="{{ asset('storage') }}/images/avatars/06.png" alt="...">
                            </div>
                            <button type="button" class="btn btn-outline-primary btn-sm radius-30 px-4"><ion-icon
                                    name="image-sharp"></ion-icon>{{ __('Change Photo') }}</button>
                        </div>

                        @include('profile.partials.update-profile-information-form')
                        @include('profile.partials.update-password-form')
                    </form>
                </div>
            </div>
        </div>
    </div><!--end row-->
</x-app-layout>
