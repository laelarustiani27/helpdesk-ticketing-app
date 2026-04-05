@extends('layouts.admin')

@section('title', __('app.settings'))

@section('content')
<div class="dashboard-container">

    @if(session('success'))
    <div id="successToast" style="position:fixed; bottom:24px; right:24px; z-index:9999;
                background:#0f172a; color:#fff; padding:12px 20px; border-radius:10px;
                font-size:13px; font-weight:500; box-shadow:0 8px 24px rgba(0,0,0,0.15);
                border:1px solid #10b981; opacity:0; transition: opacity 0.3s ease;">
        ✓ {{ session('success') }}
    </div>
    @endif

    @if($errors->any())
    <div style="background:rgba(239,68,68,0.1); border:1px solid rgba(239,68,68,0.3); color:#ef4444;
                padding:12px 16px; border-radius:10px; font-size:13px; font-weight:500; margin-bottom:20px;">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
    @endif

    <div style="margin-bottom:28px;">
        <h2 style="font-size:22px; font-weight:700; color:#1e293b; margin:0 0 2px;">{{ __('app.settings') }}</h2>
        <p style="font-size:13px; color:#94a3b8; margin:0;">{{ __('app.settings_desc') }}</p>
    </div>

    <p style="font-size:11px; font-weight:700; color:#94a3b8; letter-spacing:0.08em; text-transform:uppercase; margin:28px 0 10px;">{{ __('app.security') }}</p>
    <div style="background:#fff; border-radius:16px; box-shadow:0 1px 4px rgba(0,0,0,0.06); margin-bottom:28px; overflow:hidden;">

        <div style="padding:18px 20px; border-bottom:1px solid #f1f5f9;">
            <div style="display:flex; align-items:center; gap:14px; cursor:pointer;"
                 onclick="toggleSection('changePassword')">
                <div style="width:40px; height:40px; border-radius:10px; background:rgba(99,102,241,0.1);
                            display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#6366f1" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                </div>
                <div style="flex:1;">
                    <div style="font-size:14px; font-weight:600; color:#1e293b;">{{ __('app.change_password') }}</div>
                    <div style="font-size:12px; color:#94a3b8; margin-top:1px;">{{ __('app.change_password_desc') }}</div>
                </div>
                <svg id="changePasswordArrow" width="16" height="16" fill="none" viewBox="0 0 24 24"
                     stroke="#94a3b8" stroke-width="2" style="transition:transform 0.2s;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </div>

            <div id="changePassword" style="display:none; margin-top:16px; padding-top:16px; border-top:1px solid #f1f5f9;">
                <form method="POST" action="{{ route('admin.settings.password') }}">
                    @csrf
                    @method('PUT')
                    <div style="display:flex; flex-direction:column; gap:12px;">
                        <div>
                            <label style="font-size:12px; font-weight:600; color:#374151; display:block; margin-bottom:5px;">{{ __('app.old_password') }}</label>
                            <input type="password" name="current_password" required
                                   style="width:100%; padding:9px 12px; border:1px solid #e2e8f0; border-radius:8px;
                                          font-size:13px; color:#1e293b; background:#f8fafc; outline:none; box-sizing:border-box;"
                                   onfocus="this.style.borderColor='#2bb0a6'" onblur="this.style.borderColor='#e2e8f0'">
                        </div>
                        <div>
                            <label style="font-size:12px; font-weight:600; color:#374151; display:block; margin-bottom:5px;">{{ __('app.new_password') }}</label>
                            <input type="password" name="password" required
                                   style="width:100%; padding:9px 12px; border:1px solid #e2e8f0; border-radius:8px;
                                          font-size:13px; color:#1e293b; background:#f8fafc; outline:none; box-sizing:border-box;"
                                   onfocus="this.style.borderColor='#2bb0a6'" onblur="this.style.borderColor='#e2e8f0'">
                        </div>
                        <div>
                            <label style="font-size:12px; font-weight:600; color:#374151; display:block; margin-bottom:5px;">{{ __('app.confirm_password') }}</label>
                            <input type="password" name="password_confirmation" required
                                   style="width:100%; padding:9px 12px; border:1px solid #e2e8f0; border-radius:8px;
                                          font-size:13px; color:#1e293b; background:#f8fafc; outline:none; box-sizing:border-box;"
                                   onfocus="this.style.borderColor='#2bb0a6'" onblur="this.style.borderColor='#e2e8f0'">
                        </div>
                        <div style="display:flex; justify-content:flex-end;">
                            <button type="submit"
                                    style="padding:9px 22px; background:linear-gradient(135deg,#2bb0a6,#059669);
                                           color:#fff; border:none; border-radius:8px; font-size:13px; font-weight:600; cursor:pointer;">
                                {{ __('app.save_password') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <p style="font-size:11px; font-weight:700; color:#94a3b8; letter-spacing:0.08em; text-transform:uppercase; margin:0 0 10px;">{{ __('app.notifications') }}</p>
    <div style="background:#fff; border-radius:16px; box-shadow:0 1px 4px rgba(0,0,0,0.06); margin-bottom:28px; overflow:hidden;">

        <form method="POST" action="{{ route('admin.settings.notifications') }}">
            @csrf
            @method('PUT')

            <div style="padding:18px 20px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:14px;">
                <div style="width:40px; height:40px; border-radius:10px; background:rgba(43,176,166,0.1);
                            display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#2bb0a6" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                </div>
                <div style="flex:1;">
                    <div style="font-size:14px; font-weight:600; color:#1e293b;">{{ __('app.notifications') }}</div>
                    <div style="font-size:12px; color:#94a3b8; margin-top:1px;">{{ __('app.notifications_desc') }}</div>
                </div>
                <label style="position:relative; display:inline-block; width:44px; height:24px; cursor:pointer;">
                    <input type="checkbox" name="notif_enabled" value="1"
                           {{ ($settings['notif_enabled'] ?? true) ? 'checked' : '' }}
                           onchange="this.form.submit()"
                           style="opacity:0; width:0; height:0; position:absolute;">
                    <span style="position:absolute; inset:0; background:{{ ($settings['notif_enabled'] ?? true) ? '#2bb0a6' : '#e2e8f0' }};
                                 border-radius:24px; transition:background 0.2s;"></span>
                    <span style="position:absolute; top:3px; left:{{ ($settings['notif_enabled'] ?? true) ? '23px' : '3px' }};
                                 width:18px; height:18px; background:#fff; border-radius:50%; transition:left 0.2s; box-shadow:0 1px 3px rgba(0,0,0,0.2);"></span>
                </label>
            </div>

            <div style="padding:18px 20px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:14px;">
                <div style="width:40px; height:40px; border-radius:10px; background:rgba(245,158,11,0.1);
                            display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#f59e0b" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div style="flex:1;">
                    <div style="font-size:14px; font-weight:600; color:#1e293b;">{{ __('app.new_ticket') }}</div>
                    <div style="font-size:12px; color:#94a3b8; margin-top:1px;">{{ __('app.new_ticket_desc') }}</div>
                </div>
                <label style="position:relative; display:inline-block; width:44px; height:24px; cursor:pointer;">
                    <input type="checkbox" name="notif_ticket" value="1"
                           {{ ($settings['notif_ticket'] ?? true) ? 'checked' : '' }}
                           onchange="this.form.submit()"
                           style="opacity:0; width:0; height:0; position:absolute;">
                    <span style="position:absolute; inset:0; background:{{ ($settings['notif_ticket'] ?? true) ? '#2bb0a6' : '#e2e8f0' }};
                                 border-radius:24px;"></span>
                    <span style="position:absolute; top:3px; left:{{ ($settings['notif_ticket'] ?? true) ? '23px' : '3px' }};
                                 width:18px; height:18px; background:#fff; border-radius:50%; box-shadow:0 1px 3px rgba(0,0,0,0.2);"></span>
                </label>
            </div>

            <div style="padding:18px 20px; display:flex; align-items:center; gap:14px;">
                <div style="width:40px; height:40px; border-radius:10px; background:rgba(99,102,241,0.1);
                            display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#6366f1" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div style="flex:1;">
                    <div style="font-size:14px; font-weight:600; color:#1e293b;">{{ __('app.assign_technician') }}</div>
                    <div style="font-size:12px; color:#94a3b8; margin-top:1px;">{{ __('app.assign_technician_desc') }}</div>
                </div>
                <label style="position:relative; display:inline-block; width:44px; height:24px; cursor:pointer;">
                    <input type="checkbox" name="notif_assign" value="1"
                           {{ ($settings['notif_assign'] ?? true) ? 'checked' : '' }}
                           onchange="this.form.submit()"
                           style="opacity:0; width:0; height:0; position:absolute;">
                    <span style="position:absolute; inset:0; background:{{ ($settings['notif_assign'] ?? true) ? '#2bb0a6' : '#e2e8f0' }};
                                 border-radius:24px;"></span>
                    <span style="position:absolute; top:3px; left:{{ ($settings['notif_assign'] ?? true) ? '23px' : '3px' }};
                                 width:18px; height:18px; background:#fff; border-radius:50%; box-shadow:0 1px 3px rgba(0,0,0,0.2);"></span>
                </label>
            </div>

        </form>
    </div>

    <p style="font-size:11px; font-weight:700; color:#94a3b8; letter-spacing:0.08em; text-transform:uppercase; margin:0 0 10px;">{{ __('app.preferences') }}</p>
    <div style="background:#fff; border-radius:16px; box-shadow:0 1px 4px rgba(0,0,0,0.06); margin-bottom:28px; overflow:hidden;">

        <form method="POST" action="{{ route('admin.settings.preferences') }}">
            @csrf
            @method('PUT')

            <div style="padding:18px 20px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:14px;">
                <div style="width:40px; height:40px; border-radius:10px; background:rgba(16,185,129,0.1);
                            display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#10b981" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 5h12M9 3v2m1.048 9.5A18.022 18.022 0 016.412 9m6.088 9h7M11 21l5-10 5 10M12.751 5C11.783 10.77 8.07 15.61 3 18.129"/>
                    </svg>
                </div>
                <div style="flex:1;">
                    <div style="font-size:14px; font-weight:600; color:#1e293b;">{{ __('app.language') }}</div>
                    <div style="font-size:12px; color:#94a3b8; margin-top:1px;">{{ __('app.language_desc') }}</div>
                </div>
                <select name="language" onchange="this.form.submit()"
                        style="padding:7px 12px; border:1px solid #e2e8f0; border-radius:8px; font-size:13px;
                               color:#1e293b; background:#f8fafc; outline:none; cursor:pointer;">
                    <option value="id" {{ ($settings['language'] ?? 'id') === 'id' ? 'selected' : '' }}>Indonesia</option>
                    <option value="en" {{ ($settings['language'] ?? 'id') === 'en' ? 'selected' : '' }}>English</option>
                    <option value="de" {{ ($settings['language'] ?? 'id') === 'de' ? 'selected' : '' }}>Deutsch</option>
                </select>
            </div>

            <div style="padding:18px 20px; display:flex; align-items:center; gap:14px;">
                <div style="width:40px; height:40px; border-radius:10px; background:rgba(139,92,246,0.1);
                            display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                    <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#8b5cf6" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                </div>
                <div style="flex:1;">
                    <div style="font-size:14px; font-weight:600; color:#1e293b;">{{ __('app.theme') }}</div>
                    <div style="font-size:12px; color:#94a3b8; margin-top:1px;">{{ __('app.theme_desc') }}</div>
                </div>
                <select name="theme" onchange="this.form.submit()"
                        style="padding:7px 12px; border:1px solid #e2e8f0; border-radius:8px; font-size:13px;
                               color:#1e293b; background:#f8fafc; outline:none; cursor:pointer;">
                    <option value="light" {{ ($settings['theme'] ?? 'light') === 'light' ? 'selected' : '' }}>Light</option>
                    <option value="dark"  {{ ($settings['theme'] ?? 'light') === 'dark'  ? 'selected' : '' }}>Dark</option>
                </select>
            </div>

        </form>
    </div>

    <p style="font-size:11px; font-weight:700; color:#94a3b8; letter-spacing:0.08em; text-transform:uppercase; margin:0 0 10px;">{{ __('app.backup') }}</p>
    <div style="background:#fff; border-radius:16px; box-shadow:0 1px 4px rgba(0,0,0,0.06); margin-bottom:28px; overflow:hidden;">

        <div style="padding:18px 20px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:14px;">
            <div style="width:40px; height:40px; border-radius:10px; background:rgba(16,185,129,0.1);
                        display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#10b981" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div style="flex:1;">
                <div style="font-size:14px; font-weight:600; color:#1e293b;">{{ __('app.export_csv') }}</div>
                <div style="font-size:12px; color:#94a3b8; margin-top:1px;">{{ __('app.export_csv_desc') }}</div>
            </div>
            <a href="{{ route('admin.settings.export.csv') }}"
               style="display:inline-flex; align-items:center; gap:8px; padding:9px 22px;
                      background:linear-gradient(135deg,#2bb0a6,#059669); color:#fff;
                      border-radius:10px; font-size:13px; font-weight:600; text-decoration:none; white-space:nowrap;">
                {{ __('app.download') }}
            </a>
        </div>

        <div style="padding:18px 20px; border-bottom:1px solid #f1f5f9; display:flex; align-items:center; gap:14px;">
            <div style="width:40px; height:40px; border-radius:10px; background:rgba(245,158,11,0.1);
                        display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#f59e0b" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            <div style="flex:1;">
                <div style="font-size:14px; font-weight:600; color:#1e293b;">{{ __('app.export_by_month') }}</div>
                <div style="font-size:12px; color:#94a3b8; margin-top:1px;">{{ __('app.export_by_month_desc') }}</div>
            </div>
            <form method="GET" action="{{ route('admin.settings.export.month') }}" style="display:flex; gap:8px; align-items:center;">
                <input type="month" name="bulan" value="{{ now()->format('Y-m') }}"
                       style="padding:7px 10px; border:1px solid #e2e8f0; border-radius:8px; font-size:12px;
                              color:#1e293b; background:#f8fafc; outline:none; cursor:pointer;">
                <button type="submit"
                        style="padding:9px 22px; background:linear-gradient(135deg,#f59e0b,#d97706); color:#fff;
                               border:none; border-radius:10px; font-size:13px; font-weight:600; cursor:pointer; white-space:nowrap;">
                    {{ __('app.download') }}
                </button>
            </form>
        </div>

        <div style="padding:18px 20px; display:flex; align-items:center; gap:14px;">
            <div style="width:40px; height:40px; border-radius:10px; background:rgba(99,102,241,0.1);
                        display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#6366f1" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
            </div>
            <div style="flex:1;">
                <div style="font-size:14px; font-weight:600; color:#1e293b;">{{ __('app.auto_backup') }}</div>
                <div style="font-size:12px; color:#94a3b8; margin-top:1px;">{{ __('app.auto_backup_desc') }}</div>
            </div>
            <form method="POST" action="{{ route('admin.settings.export.backup-toggle') }}" style="margin:0;">
                @csrf
                <button type="submit"
                        style="position:relative; width:44px; height:24px; border-radius:24px; border:none; cursor:pointer;
                               background:{{ $autoBackup ? '#6366f1' : '#e2e8f0' }}; transition:background 0.2s; padding:0;">
                    <span style="position:absolute; top:3px; width:18px; height:18px; border-radius:50%; background:#fff;
                                 box-shadow:0 1px 3px rgba(0,0,0,0.2); transition:left 0.2s;
                                 left:{{ $autoBackup ? '23px' : '3px' }};"></span>
                </button>
            </form>
        </div>

    </div>

</div>

<script>
function toggleSection(id) {
    const el    = document.getElementById(id);
    const arrow = document.getElementById(id + 'Arrow');
    const open  = el.style.display === 'none';
    el.style.display = open ? 'block' : 'none';
    if (arrow) arrow.style.transform = open ? 'rotate(90deg)' : 'rotate(0deg)';
}

document.addEventListener('DOMContentLoaded', function() {
    const toast = document.getElementById('successToast');
    if (toast) {
        setTimeout(() => { toast.style.opacity = '1'; }, 100);
        setTimeout(() => {
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 2000);
    }
});
</script>

@endsection