@extends('layouts.app')

@section('title', 'Profile Settings')

@section('content')
<div class="row">
    <div class="col-lg-8 mx-auto">
        <h2 class="mb-4 fw-bold">User Profile Settings</h2>

        {{-- Display current role for clarity --}}
        <div class="alert alert-info">
            Your current role is: <span class="fw-bold">{{ Auth::user()->role }}</span>
        </div>
        
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg mb-4">
            <div class="max-w-xl">
                {{-- This includes the form to update name and email --}}
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg mb-4">
            <div class="max-w-xl">
                {{-- This includes the form to update password --}}
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                {{-- This includes the form to delete account --}}
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection