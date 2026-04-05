@extends('layouts.admin')

@section('title', __('app.my_profile'))

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endpush

@section('content')
<div class="profile-wrapper">

    {{-- Header --}}
    <div class="profile-header">
        <div class="profile-header-avatar">
            {{ strtoupper(substr(auth()->user()->nama_lengkap ?? 'A', 0, 2)) }}
        </div>
        <div class="profile-header-info">
            <h2>{{ __('app.profile_welcome') }}, {{ auth()->user()->nama_lengkap ?? 'Admin' }}</h2>
            <div class="profile-header-meta">
                <span>{{ __('app.profile_last_online') }} {{ auth()->user()->updated_at?->format('j F Y') ?? '-' }}</span>
                <span class="profile-dot">·</span>
                <span>{{ ucfirst(auth()->user()->role ?? 'Admin') }}</span>
            </div>
        </div>
    </div>

    <div class="profile-divider"></div>

    {{-- Main Content --}}
    <div class="profile-main">

        @if(session('success'))
        <div class="profile-alert profile-alert-success">
            {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="profile-alert profile-alert-error">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <h3 class="profile-section-title">{{ __('app.profile_personal_info') }}</h3>

        {{-- Avatar --}}
        <div class="profile-avatar-row">
            <div class="profile-avatar-circle">
                {{ strtoupper(substr(auth()->user()->nama_lengkap ?? 'A', 0, 2)) }}
            </div>
            <div class="profile-avatar-info">
                <span class="profile-avatar-change">{{ __('app.profile_change_photo') }}</span>
                <span class="profile-avatar-hint">{{ __('app.profile_photo_hint') }}</span>
            </div>
        </div>

        {{-- Form --}}
        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')

            <div class="profile-form-grid">
                <div class="profile-form-group">
                    <label>{{ __('app.tek_nama_lengkap') }}</label>
                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', auth()->user()->nama_lengkap) }}" placeholder="{{ __('app.tek_nama_lengkap') }}">
                </div>
                <div class="profile-form-group">
                    <label>{{ __('app.tek_username') }}</label>
                    <input type="text" name="username" value="{{ old('username', auth()->user()->username) }}" placeholder="{{ __('app.tek_username') }}">
                </div>
                <div class="profile-form-group profile-span-2">
                    <label>{{ __('app.profile_email_address') }}</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" placeholder="{{ __('app.tek_email') }}">
                </div>
                <div class="profile-form-group">
                    <label>{{ __('app.profile_role') }}</label>
                    <input type="text" value="{{ ucfirst(auth()->user()->role ?? '-') }}" disabled>
                </div>
                <div class="profile-form-group">
                    <label>{{ __('app.tek_bergabung') }}</label>
                    <input type="text" value="{{ auth()->user()->created_at?->format('d F Y') ?? '-' }}" disabled>
                </div>
            </div>

            <button type="submit" class="profile-submit-btn">{{ __('app.profile_edit_info') }}</button>
        </form>
    </div>

</div>
@endsection