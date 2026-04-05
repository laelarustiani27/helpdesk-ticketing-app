@extends('layouts.teknisi')

@section('title', __('app.tek_settings_title'))

@push('styles')
<link rel="stylesheet" href="{{ asset('css/settings.css') }}">
@endpush

@section('content')
<div class="dashboard-container">

    <div class="page-header" style="margin-bottom: 24px;">
        <h1 class="page-title">{{ __('app.tek_settings_title') }}</h1>
        <p class="page-sub">{{ __('app.tek_settings_sub') }}</p>
    </div>

    @if(session('success'))
    <div class="stt-alert stt-alert-success">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div class="stt-alert stt-alert-error">
        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>@foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach</div>
    </div>
    @endif

    {{-- KEAMANAN --}}
    <div class="stt-section-label">{{ __('app.tek_keamanan') }}</div>
    <div class="stt-group">
        <div class="stt-item stt-item-expandable" onclick="toggleExpand('password')">
            <div class="stt-item-icon" style="background:rgba(239,68,68,0.1);">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#ef4444" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
            </div>
            <div class="stt-item-info">
                <div class="stt-item-title">{{ __('app.tek_ganti_password') }}</div>
                <div class="stt-item-sub">{{ __('app.tek_ganti_password_sub') }}</div>
            </div>
            <svg class="stt-chevron" id="chevron-password" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
        </div>
        <div class="stt-expand-body" id="expand-password" style="display:none;">
            <form method="POST" action="{{ route('teknisi.profile.password') }}">
                @csrf @method('PUT')
                <div class="stt-form-grid stt-form-grid-1col">
                    <div class="stt-form-group">
                        <label>{{ __('app.tek_password_lama') }}</label>
                        <div class="stt-pw-wrap">
                            <input type="password" name="current_password" id="pw1" placeholder="••••••••">
                            <span class="stt-pw-eye" onclick="togglePw('pw1',this)"><svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></span>
                        </div>
                    </div>
                    <div class="stt-form-group">
                        <label>{{ __('app.tek_password_baru') }}</label>
                        <div class="stt-pw-wrap">
                            <input type="password" name="password" id="pw2" placeholder="Min. 8">
                            <span class="stt-pw-eye" onclick="togglePw('pw2',this)"><svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></span>
                        </div>
                    </div>
                    <div class="stt-form-group">
                        <label>{{ __('app.tek_konfirmasi_password') }}</label>
                        <div class="stt-pw-wrap">
                            <input type="password" name="password_confirmation" id="pw3" placeholder="••••••••">
                            <span class="stt-pw-eye" onclick="togglePw('pw3',this)"><svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg></span>
                        </div>
                    </div>
                </div>
                <div class="stt-form-actions">
                    <button type="button" onclick="toggleExpand('password')" class="stt-btn-cancel">{{ __('app.tek_batal') }}</button>
                    <button type="submit" class="stt-btn-save">
                        <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                        {{ __('app.tek_simpan') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- NOTIFIKASI --}}
    <div class="stt-section-label">{{ __('app.tek_notifikasi') }}</div>
    <div class="stt-group">
        <div class="stt-item">
            <div class="stt-item-icon" style="background:rgba(43,176,166,0.1);">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#2bb0a6" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
            </div>
            <div class="stt-item-info">
                <div class="stt-item-title">{{ __('app.tek_notif_semua') }}</div>
                <div class="stt-item-sub">{{ __('app.tek_notif_semua_sub') }}</div>
            </div>
            <label class="stt-toggle">
                <input type="checkbox" id="notif-all"
                       {{ Auth::user()->notif_enabled ? 'checked' : '' }}
                       onchange="handleNotifAll(this); saveNotif()">
                <span class="stt-toggle-slider"></span>
            </label>
        </div>
        <div class="stt-item stt-item-sub-toggle" id="notif-tiket-row">
            <div class="stt-item-icon" style="background:rgba(245,158,11,0.1);">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#f59e0b" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <div class="stt-item-info">
                <div class="stt-item-title">{{ __('app.tek_notif_tiket') }}</div>
                <div class="stt-item-sub">{{ __('app.tek_notif_tiket_sub') }}</div>
            </div>
            <label class="stt-toggle">
                <input type="checkbox" id="notif-tiket"
                       {{ Auth::user()->notif_ticket ? 'checked' : '' }}
                       onchange="saveNotif()">
                <span class="stt-toggle-slider"></span>
            </label>
        </div>
        <div class="stt-item stt-item-sub-toggle" id="notif-reminder-row">
            <div class="stt-item-icon" style="background:rgba(239,68,68,0.1);">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#ef4444" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="stt-item-info">
                <div class="stt-item-title">{{ __('app.tek_notif_reminder') }}</div>
                <div class="stt-item-sub">{{ __('app.tek_notif_reminder_sub') }}</div>
            </div>
            <label class="stt-toggle">
                <input type="checkbox" id="notif-reminder"
                       {{ Auth::user()->notif_enabled ? 'checked' : '' }}
                       onchange="saveNotif()">
                <span class="stt-toggle-slider"></span>
            </label>
        </div>
        <div class="stt-item stt-item-sub-toggle" id="notif-selesai-row">
            <div class="stt-item-icon" style="background:rgba(16,185,129,0.1);">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#10b981" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div class="stt-item-info">
                <div class="stt-item-title">{{ __('app.tek_notif_selesai') }}</div>
                <div class="stt-item-sub">{{ __('app.tek_notif_selesai_sub') }}</div>
            </div>
            <label class="stt-toggle">
                <input type="checkbox" id="notif-selesai"
                       {{ Auth::user()->notif_assign ? 'checked' : '' }}
                       onchange="saveNotif()">
                <span class="stt-toggle-slider"></span>
            </label>
        </div>
    </div>

    {{-- PREFERENSI --}}
    <div class="stt-section-label">{{ __('app.tek_preferensi') }}</div>
    <div class="stt-group">
        <div class="stt-item" style="cursor:default;">
            <div class="stt-item-icon" style="background:rgba(99,102,241,0.1);">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#6366f1" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/></svg>
            </div>
            <div class="stt-item-info">
                <div class="stt-item-title">{{ __('app.tek_bahasa') }}</div>
                <div class="stt-item-sub">{{ __('app.tek_bahasa_sub') }}</div>
            </div>
            <select class="stt-select" id="lang-select" onchange="applyLang(this.value)">
                <option value="id" {{ session('locale', 'id') === 'id' ? 'selected' : '' }}>Indonesia</option>
                <option value="en" {{ session('locale', 'id') === 'en' ? 'selected' : '' }}>English</option>
                <option value="de" {{ session('locale', 'id') === 'de' ? 'selected' : '' }}>Deutsch</option>
            </select>
        </div>
        <div class="stt-item" style="cursor:default;">
            <div class="stt-item-icon" style="background:rgba(100,116,139,0.1);">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#64748b" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
            </div>
            <div class="stt-item-info">
                <div class="stt-item-title">{{ __('app.tek_tema') }}</div>
                <div class="stt-item-sub">{{ __('app.tek_tema_sub') }}</div>
            </div>
            <select class="stt-select" id="theme-select" onchange="applyTheme(this.value)">
                <option value="light" {{ session('theme', 'light') === 'light' ? 'selected' : '' }}>{{ __('app.tek_light') }}</option>
                <option value="dark"  {{ session('theme', 'light') === 'dark'  ? 'selected' : '' }}>{{ __('app.tek_dark') }}</option>
            </select>
        </div>
    </div>

    {{-- TENTANG AKUN --}}
    <div class="stt-section-label">{{ __('app.tek_tentang') }}</div>
    <div class="stt-group">
        <div class="stt-item" style="cursor:default;">
            <div class="stt-item-icon" style="background:rgba(43,176,166,0.1);">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#2bb0a6" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div class="stt-item-info">
                <div class="stt-item-title">{{ Auth::user()->nama_lengkap ?? Auth::user()->name }}</div>
                <div class="stt-item-sub">{{ Auth::user()->email }}</div>
            </div>
            <span class="stt-role-badge">{{ __('app.technician') }}</span>
        </div>
        <div class="stt-item" style="cursor:default;">
            <div class="stt-item-icon" style="background:rgba(245,158,11,0.1);">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#f59e0b" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div class="stt-item-info">
                <div class="stt-item-title">{{ __('app.tek_bergabung') }}</div>
                <div class="stt-item-sub">{{ Auth::user()->created_at->format('d F Y') }}</div>
            </div>
        </div>
    </div>


</div>
@endsection

@push('scripts')
<script>
window.sttHasPasswordError = @json($errors->has('current_password') || $errors->has('password'));
window.sttHasProfilError   = @json($errors->has('nama_lengkap') || $errors->has('username') || $errors->has('email'));
window.notifRoute          = "{{ route('teknisi.settings.notifications') }}";
window.csrfToken           = "{{ csrf_token() }}";
</script>
<script src="{{ asset('js/settings.js') }}"></script>
@endpush