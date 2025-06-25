@extends('layouts.admin')

@section('content')
    <div class="min-h-screen bg-pink-200 sm:ml-64 pt-28 pb-12 px-6">
        <div class="max-w-8xl mx-auto space-y-8">
            <div class="p-8 bg-white shadow-lg rounded-2xl">
                <div>
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-8 bg-white shadow-lg rounded-2xl">
                <div>
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-8 bg-white shadow-lg rounded-2xl">
                <div>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection
