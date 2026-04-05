@extends('layouts.admin')

@section('title', __('app.technician_list'))

@section('content')
<div class="dashboard-container">

    <div style="margin-bottom:28px;">
        <h2 style="font-size:22px; font-weight:700; color:#1e293b; margin:0 0 2px;">{{ __('app.technician') }}</h2>
        <p style="font-size:13px; color:#94a3b8; margin:0;">{{ __('app.technician_sub') }}</p>
    </div>

    <div class="panel-card teknisi-card">
        <div class="teknisi-toolbar">
            <div class="teknisi-toolbar-left">
                <div class="teknisi-total">
                    <div class="teknisi-total-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#2bb0a6" stroke-width="2">
                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                            <circle cx="9" cy="7" r="4"/>
                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                        </svg>
                    </div>
                    <span class="filter-label">
                        {{ __('app.total_technician') }}: <strong>{{ $totalTeknisi }} {{ __('app.people') }}</strong>
                    </span>
                    <span class="teknisi-divider">|</span>
                    <span class="filter-label">
                        {{ __('app.active') }}: <strong class="text-active">{{ $totalAktif }} {{ __('app.people') }}</strong>
                    </span>
                </div>

                <form method="GET" action="{{ route('admin.teknisi.index') }}">
                    <div class="search-box">
                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="#7d8590" stroke-width="2">
                            <circle cx="11" cy="11" r="8"/><path d="M21 21l-4.35-4.35"/>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="{{ __('app.search_technician') }}">
                    </div>
                </form>
            </div>

            <div class="teknisi-toolbar-right">
                <button class="teknisi-btn-add" id="btnTambahTeknisi">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/>
                    </svg>
                    {{ __('app.add_technician') }}
                </button>
                <button class="filter-select teknisi-btn-toolbar">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"/>
                    </svg>
                    {{ __('app.filter') }}
                </button>
                <button class="filter-select teknisi-btn-toolbar" onclick="window.hapusSelected()" style="color:#ef4444;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="3 6 5 6 21 6"/>
                        <path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/>
                        <path d="M10 11v6"/><path d="M14 11v6"/>
                        <path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/>
                    </svg>
                    Hapus
                </button>
            </div>
        </div>

        <div class="teknisi-table-wrap">
            <table class="teknisi-table">
                <thead>
                    <tr class="teknisi-thead-row">
                        <th class="teknisi-th" style="width:48px; text-align:center;">
                            <input type="checkbox" id="checkAll"
                                   style="width:16px; height:16px; cursor:pointer; accent-color:#2bb0a6;">
                        </th>
                        <th class="teknisi-th">{{ __('app.name') }}</th>
                        <th class="teknisi-th">{{ __('app.email') }}</th>
                        <th class="teknisi-th">{{ __('app.specialization') }}</th>
                        <th class="teknisi-th">{{ __('app.status') }}</th>
                        <th class="teknisi-th">{{ __('app.action') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teknisi as $item)
                    @php
                        $specColors   = ['Networking'=>'#3B82F6','Hardware'=>'#F59E0B','Software'=>'#8B5CF6','CCTV'=>'#10B981'];
                        $dc           = $specColors[$item->spesialisasi] ?? '#2bb0a6';
                        $isActive     = $item->status === 'Aktif';
                        $initials     = collect(explode(' ', $item->name))->take(2)->map(fn($w) => strtoupper($w[0]))->implode('');
                        $avatarColors = ['#2bb0a6','#45B7D1','#4ECDC4','#96CEB4','#85C1E9','#20a89e','#1a7a72','#60a5fa','#34d399','#5eead4'];
                        $avatarColor  = $avatarColors[$item->id % count($avatarColors)];
                    @endphp
                    <tr class="teknisi-row">
                        <td class="teknisi-td" style="width:48px; text-align:center;">
                            <input type="checkbox" name="selected[]" value="{{ $item->id }}"
                                style="width:16px; height:16px; cursor:pointer; accent-color:#2bb0a6;">
                        </td>
                        <td class="teknisi-td">
                            <div class="teknisi-name-wrap">
                                <div class="teknisi-avatar" style="background:{{ $avatarColor }};">{{ $initials }}</div>
                                <div>
                                    <div class="teknisi-name">{{ $item->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="teknisi-td">
                            <span style="font-size:13px; color:#64748b;">{{ $item->email }}</span>
                        </td>
                        <td class="teknisi-td">
                            <span class="teknisi-spec">
                                <span class="teknisi-spec-dot" style="background:{{ $dc }};"></span>
                                {{ $item->spesialisasi }}
                            </span>
                        </td>
                        <td class="teknisi-td">
                            <div style="display:flex; flex-direction:column; gap:4px;">
                                <span class="teknisi-badge"
                                    style="background:{{ $isActive ? '#ECFDF5' : '#F3F4F6' }};
                                            color:{{ $isActive ? '#10B981' : '#9CA3AF' }};">
                                    <span class="teknisi-status-dot" style="background:{{ $isActive ? '#10B981' : '#9CA3AF' }};"></span>
                                    {{ $item->status }}
                                </span>
                                @if($item->last_login)
                                    <span style="font-size:11px; color:#94a3b8;">
                                        {{ $item->last_login->diffForHumans() }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="teknisi-td">
                            <div class="teknisi-action-wrap">
                                <button class="teknisi-action-btn" data-id="{{ $item->id }}">
                                    <svg width="14" height="14" viewBox="0 0 24 24" fill="#6B7280">
                                        <circle cx="12" cy="5" r="1.5"/>
                                        <circle cx="12" cy="12" r="1.5"/>
                                        <circle cx="12" cy="19" r="1.5"/>
                                    </svg>
                                </button>
                                <div class="teknisi-dropdown" id="dropdown-{{ $item->id }}">
                                    <button type="button" class="teknisi-dropdown-item btn-assign-teknisi"
                                        data-id="{{ $item->id }}"
                                        data-name="{{ $item->name }}">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                                            <circle cx="9" cy="7" r="4"/>
                                            <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                                            <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                                        </svg>
                                        Assign Tiket
                                    </button>
                                    <button type="button" class="teknisi-dropdown-item btn-edit-teknisi"
                                        data-id="{{ $item->id }}"
                                        data-name="{{ $item->name }}"
                                        data-email="{{ $item->email }}"
                                        data-spesialisasi="{{ $item->spesialisasi }}"
                                        data-status="{{ $item->status }}">
                                        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                        {{ __('app.edit') }}
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="teknisi-empty">{{ __('app.no_technician_found') }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="teknisi-pagination">
            <span class="teknisi-pagination-info">
                {{ __('app.showing') }} {{ $teknisi->firstItem() ?? 0 }} {{ __('app.to') }} {{ $teknisi->lastItem() ?? 0 }}
                {{ __('app.of') }} {{ $teknisi->total() }} {{ __('app.data') }}
            </span>
            <div class="teknisi-pagination-nav">
                @if($teknisi->onFirstPage())
                    <span class="teknisi-page-btn disabled">← {{ __('app.prev') }}</span>
                @else
                    <a href="{{ $teknisi->previousPageUrl() }}" class="teknisi-page-btn">← {{ __('app.prev') }}</a>
                @endif
                @foreach($teknisi->getUrlRange(1, $teknisi->lastPage()) as $page => $url)
                    <a href="{{ $url }}" class="teknisi-page-num {{ $page == $teknisi->currentPage() ? 'active' : '' }}">{{ $page }}</a>
                @endforeach
                @if($teknisi->hasMorePages())
                    <a href="{{ $teknisi->nextPageUrl() }}" class="teknisi-page-btn">{{ __('app.next') }} →</a>
                @else
                    <span class="teknisi-page-btn disabled">{{ __('app.next') }} →</span>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('modals')
{{-- Modal Tambah Teknisi --}}
<div id="teknisiModal">
    <div id="teknisiModalBox">
        <div class="tm-header">
            <div class="tm-title">
                <span class="tm-title-bar"></span>
                {{ __('app.add_technician') }}
            </div>
            <button class="tm-close" id="tmClose">✕</button>
        </div>
        <form method="POST" action="{{ route('admin.teknisi.store') }}" id="formTambahTeknisi">
            @csrf
            <div class="tm-body">
                @if($errors->any())
                    <div style="background:#fef2f2; border:1px solid #fecaca; border-radius:8px; padding:10px 14px; margin-bottom:12px;">
                        @foreach($errors->all() as $error)
                            <p style="color:#ef4444; font-size:13px; margin:2px 0;">• {{ $error }}</p>
                        @endforeach
                    </div>
                @endif
                <div class="tm-field">
                    <label class="tm-label">{{ __('app.full_name') }} <span style="color:#ef4444;">*</span></label>
                    <input class="tm-input" type="text" name="name" required>
                </div>
                <div class="tm-field">
                    <label class="tm-label">{{ __('app.email') }} <span style="color:#ef4444;">*</span></label>
                    <input class="tm-input" type="email" name="email" required>
                    <small style="font-size:12px; color:#94a3b8; margin-top:4px; display:block;">{{ __('app.email_example') }}</small>
                </div>
                <div class="tm-field">
                    <label class="tm-label">{{ __('app.password') }} <span style="color:#ef4444;">*</span></label>
                    <input class="tm-input" type="password" name="password" required>
                    <small style="font-size:12px; color:#94a3b8; margin-top:4px; display:block;">{{ __('app.password_min') }}</small>
                </div>
                <div class="tm-field">
                    <label class="tm-label">{{ __('app.specialization') }} <span style="color:#ef4444;">*</span></label>
                    <select class="tm-select" name="spesialisasi" required>
                        <option value="">{{ __('app.select_specialization') }}</option>
                        <option value="Networking">Networking</option>
                        <option value="Hardware">Hardware</option>
                        <option value="Software">Software</option>
                        <option value="CCTV">CCTV</option>
                        <option value="Lainnya">{{ __('app.other') }}</option>
                    </select>
                </div>
                <div class="tm-field">
                    <label class="tm-label">{{ __('app.status') }}</label>
                    <div class="tm-status-row">
                        <label class="tm-status-opt active" id="optAktif">
                            <input type="radio" name="status" value="Aktif" checked style="display:none;">
                            <span class="tm-status-dot"></span>
                            {{ __('app.active') }}
                        </label>
                        <label class="tm-status-opt" id="optNonaktif">
                            <input type="radio" name="status" value="Tidak Aktif" style="display:none;">
                            <span class="tm-status-dot"></span>
                            {{ __('app.inactive') }}
                        </label>
                    </div>
                </div>
            </div>
            <div class="tm-footer">
                <button type="button" class="tm-btn-cancel" id="tmBatal">{{ __('app.cancel') }}</button>
                <button type="submit" class="tm-btn-submit">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    {{ __('app.save_technician') }}
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit Teknisi --}}
<div id="editTeknisiModal">
    <div id="teknisiModalBox">
        <div class="tm-header">
            <div class="tm-title">
                <span class="tm-title-bar"></span>
                Edit Teknisi
            </div>
            <button class="tm-close" id="tmEditClose">✕</button>
        </div>
        <form method="POST" id="formEditTeknisi" action="">
            @csrf
            @method('PUT')
            <div class="tm-body">
                <div class="tm-field">
                    <label class="tm-label">Nama Lengkap <span style="color:#ef4444;">*</span></label>
                    <input class="tm-input" type="text" name="name" id="editName" required>
                </div>
                <div class="tm-field">
                    <label class="tm-label">Email <span style="color:#ef4444;">*</span></label>
                    <input class="tm-input" type="email" name="email" id="editEmail" required>
                </div>
                <div class="tm-field">
                    <label class="tm-label">Password <small style="color:#94a3b8;">(Kosongkan jika tidak ingin mengubah)</small></label>
                    <input class="tm-input" type="password" name="password" id="editPassword">
                </div>
                <div class="tm-field">
                    <label class="tm-label">Spesialisasi <span style="color:#ef4444;">*</span></label>
                    <select class="tm-select" name="spesialisasi" id="editSpesialisasi" required>
                        <option value="">Pilih Spesialisasi</option>
                        <option value="Networking">Networking</option>
                        <option value="Hardware">Hardware</option>
                        <option value="Software">Software</option>
                        <option value="CCTV">CCTV</option>
                    </select>
                </div>
                <div class="tm-field">
                    <label class="tm-label">Status</label>
                    <div class="tm-status-row">
                        <label class="tm-status-opt" id="editOptAktif">
                            <input type="radio" name="status" value="Aktif" style="display:none;">
                            <span class="tm-status-dot"></span>
                            Aktif
                        </label>
                        <label class="tm-status-opt" id="editOptNonaktif">
                            <input type="radio" name="status" value="Tidak Aktif" style="display:none;">
                            <span class="tm-status-dot"></span>
                            Tidak Aktif
                        </label>
                    </div>
                </div>
            </div>
            <div class="tm-footer">
                <button type="button" class="tm-btn-cancel" id="tmEditBatal">Batal</button>
                <button type="submit" class="tm-btn-submit">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Assign Tiket ke Teknisi --}}
<div id="assignTeknisiModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:9999;align-items:center;justify-content:center;">
    <div style="background:#fff;border-radius:16px;width:100%;max-width:480px;overflow:hidden;box-shadow:0 24px 64px rgba(0,0,0,0.2);">
        <div class="tm-header">
            <div class="tm-title">
                <span class="tm-title-bar"></span>
                Assign Tiket ke <span id="assignTeknisiName" style="color:#2bb0a6;"></span>
            </div>
            <button class="tm-close" id="closeAssignTeknisi">✕</button>
        </div>
        <div class="tm-body">
            <p style="font-size:13px;color:#64748b;margin-bottom:14px;">Pilih tiket yang belum ditugaskan:</p>
            <div id="assignTicketList" style="display:flex;flex-direction:column;gap:8px;max-height:320px;overflow-y:auto;">
                <div style="text-align:center;color:#94a3b8;font-size:13px;padding:20px;">Memuat tiket...</div>
            </div>
        </div>
        <div class="tm-footer">
            <button type="button" class="tm-btn-cancel" id="cancelAssignTeknisi">Batal</button>
            <button type="button" class="tm-btn-submit" id="confirmAssignTeknisi">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12"/>
                </svg>
                Assign
            </button>
        </div>
    </div>
</div>

@endpush