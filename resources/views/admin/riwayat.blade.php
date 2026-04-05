@extends('layouts.admin')

@section('title', __('app.history'))

@push('head')
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endpush

@section('content')
<div class="dashboard-container">

    <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:28px;">
        <div>
            <h2 style="font-size:22px; font-weight:700; color:#1e293b; margin:0 0 2px;">{{ __('app.history') }}</h2>
            <p style="font-size:13px; color:#94a3b8; margin:0;">{{ __('app.history_sub') }}</p>
        </div>
        <div class="riwayat-header-actions">
            <div class="riwayat-search-wrapper">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="riwayat-search-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
                <input type="text" class="riwayat-search" id="riwayatSearch" placeholder="{{ __('app.search_history') }}">
            </div>
            <select class="riwayat-filter-select" id="riwayatPeriod">
                <option value="">{{ __('app.all_period') }}</option>
                <option value="today">{{ __('app.today') }}</option>
                <option value="week">{{ __('app.last_7_days') }}</option>
                <option value="month">{{ __('app.last_30_days') }}</option>
            </select>
        </div>
    </div>

    @if(isset($riwayat) && $riwayat->count() > 0)

        <div class="riwayat-stats">
            <div class="riwayat-stat-item">
                <span class="riwayat-stat-number">{{ $riwayat->count() }}</span>
                <span class="riwayat-stat-label">{{ __('app.total_done') }}</span>
            </div>
            <div class="riwayat-stat-divider"></div>
            <div class="riwayat-stat-item">
                <span class="riwayat-stat-number">{{ $riwayat->where('priority', 'critical')->count() }}</span>
                <span class="riwayat-stat-label">Critical</span>
            </div>
            <div class="riwayat-stat-divider"></div>
            <div class="riwayat-stat-item">
                <span class="riwayat-stat-number">{{ $riwayat->where('priority', 'high')->count() }}</span>
                <span class="riwayat-stat-label">High</span>
            </div>
            <div class="riwayat-stat-divider"></div>
            <div class="riwayat-stat-item">
                <span class="riwayat-stat-number">{{ $riwayat->where('priority', 'medium')->count() + $riwayat->where('priority', 'low')->count() }}</span>
                <span class="riwayat-stat-label">Med / Low</span>
            </div>
        </div>

        <div class="riwayat-box" id="riwayatList">
            @foreach($riwayat as $item)
            <div class="riwayat-item"
                 data-priority="{{ $item->priority ?? 'medium' }}"
                 data-title="{{ strtolower($item->judul ?? $item->title ?? '') }}"
                 data-date="{{ optional($item->resolved_at ?? $item->updated_at)->format('Y-m-d') }}">

                <div class="riwayat-item-left">
                    <div class="riwayat-priority-bar priority-{{ $item->priority ?? 'medium' }}"></div>
                    <div class="riwayat-resolved-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </div>
                </div>

                <div class="riwayat-item-content">
                    <div class="riwayat-item-header">
                        <div class="riwayat-item-title-row">
                            <h3 class="riwayat-item-title">{{ $item->judul ?? $item->title ?? $item->subject }}</h3>
                            <div class="riwayat-item-badges">
                                <span class="riwayat-priority-tag tag-{{ $item->priority ?? 'medium' }}">
                                    {{ strtoupper($item->priority ?? 'MEDIUM') }}
                                </span>
                                <span class="riwayat-resolved-badge">✓ {{ __('app.done') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="riwayat-item-meta">
                        <span class="riwayat-meta-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                            </svg>
                            {{ $item->lokasi ?? $item->location ?? '-' }}
                        </span>
                        <span class="riwayat-meta-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            {{ __('app.done') }}: {{ optional($item->resolved_at ?? $item->updated_at)->format('d M Y, H:i') ?? '-' }}
                        </span>
                        <span class="riwayat-meta-item">
                            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                            </svg>
                            {{ __('app.reported') }}: {{ optional($item->created_at)->format('d M Y, H:i') ?? '-' }}
                        </span>
                    </div>

                    <p class="riwayat-item-desc">
                        {{ Str::limit($item->deskripsi ?? $item->description ?? '-', 130) }}
                    </p>

                    @if($item->catatan_resolusi ?? $item->resolution_notes ?? false)
                    <div class="riwayat-resolution-note">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <span><strong>{{ __('app.note') }}:</strong> {{ $item->catatan_resolusi ?? $item->resolution_notes }}</span>
                    </div>
                    @endif

                    <div class="riwayat-item-footer">
                        <div class="riwayat-people">
                            <span class="riwayat-person">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0" />
                                </svg>
                                {{ __('app.reporter') }}: <strong>{{ $item->reported_by ?? '-' }}</strong>
                            </span>
                            @if($item->teknisi ?? $item->assignee ?? false)
                            <span class="riwayat-person teknisi">
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17 17.25 21A2.652 2.652 0 0 0 21 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 1 1-3.586-3.586l6.837-5.63" />
                                </svg>
                                {{ __('app.technician') }}: <strong>{{ $item->assigned_to ?? '-' }}</strong>
                            </span>
                            @endif
                        </div>
                        <div style="display:flex; align-items:center; gap:8px;">
                            <a href="{{ route('admin.riwayat.show', $item->id) }}" class="riwayat-detail-btn" data-id="{{ $item->id }}">
                                {{ __('app.view_detail') }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                            <form action="{{ route('admin.riwayat.destroy', $item->id) }}" method="POST"
                                onsubmit="return confirm('{{ __('app.confirm_delete_history') }}')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" style="display:inline-flex; align-items:center; gap:5px;
                                    padding:6px 12px; border-radius:8px; font-size:12px; font-weight:600;
                                    color:#ef4444; background:rgba(239,68,68,0.08); border:1px solid rgba(239,68,68,0.2);
                                    cursor:pointer; transition:all 0.15s; font-family:inherit;"
                                    onmouseover="this.style.background='rgba(239,68,68,0.15)'"
                                    onmouseout="this.style.background='rgba(239,68,68,0.08)'">
                                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    {{ __('app.delete_history') }}
                                </button>
                            </form>
                        </div>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="riwayat-empty" id="riwayatEmpty" style="display:none;">
            <div class="riwayat-empty-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                </svg>
            </div>
            <p>{{ __('app.no_match') }}</p>
        </div>

    @else
        <div class="riwayat-box riwayat-empty-state">
            <div class="riwayat-empty-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <p class="riwayat-empty-text">{{ __('app.no_history') }}</p>
        </div>
    @endif

</div>
@endsection

@push('scripts')
<script>
    @if(session('tab') === 'laporan')
        switchTab('laporan');
    @endif
</script>
<script src="{{ asset('js/app.js') }}"></script>
@endpush