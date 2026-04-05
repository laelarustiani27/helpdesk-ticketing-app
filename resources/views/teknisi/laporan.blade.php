@extends('layouts.teknisi')

@section('title', __('app.tek_laporan_title') . ' - NetRespond')

@section('content')
<div style="max-width:1400px; margin:0 auto; padding:24px;">

    <div class="page-header" style="margin-bottom:20px;">
        <h1>{{ __('app.tek_laporan_title') }}</h1>
        <p class="page-sub">{{ __('app.tek_laporan_sub') }}</p>
    </div>

    <div>
        <div class="issues-box">

            @if(session('success'))
                <div class="alert alert-success" style="margin-bottom:20px;">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger" style="margin-bottom:20px;">{{ session('error') }}</div>
            @endif

            <form action="{{ route('teknisi.laporan.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="judul">{{ __('app.tek_laporan_judul') }} <span style="color:#ef4444;">*</span></label>
                    <input type="text" id="judul" name="judul"
                           value="{{ old('judul') }}" required>
                    @error('judul')<span class="error">{{ $message }}</span>@enderror
                </div>

                <div class="laporan-grid">
                    <div class="form-group">
                        <label for="lokasi">{{ __('app.tek_laporan_lokasi') }} <span style="color:#ef4444;">*</span></label>
                        <input type="text" id="lokasi" name="lokasi"
                               value="{{ old('lokasi') }}" required>
                        @error('lokasi')<span class="error">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="prioritas">{{ __('app.tek_laporan_prioritas') }} <span style="color:#ef4444;">*</span></label>
                        <select id="prioritas" name="prioritas" class="filter-select" style="width:100%;" required>
                            <option value="">-- {{ __('app.select') }} --</option>
                            <option value="critical" {{ old('prioritas')=='critical' ? 'selected' : '' }}>{{ __('app.tek_laporan_p_critical') }}</option>
                            <option value="high"     {{ old('prioritas')=='high'     ? 'selected' : '' }}>{{ __('app.tek_laporan_p_high') }}</option>
                            <option value="medium"   {{ old('prioritas')=='medium'   ? 'selected' : '' }}>{{ __('app.tek_laporan_p_medium') }}</option>
                            <option value="low"      {{ old('prioritas')=='low'      ? 'selected' : '' }}>{{ __('app.tek_laporan_p_low') }}</option>
                        </select>
                        @error('prioritas')<span class="error">{{ $message }}</span>@enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="deskripsi">{{ __('app.tek_laporan_deskripsi') }} <span style="color:#ef4444;">*</span></label>
                    <textarea id="deskripsi" name="deskripsi" rows="4" required
                              class="laporan-textarea">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')<span class="error">{{ $message }}</span>@enderror
                </div>

                <div class="form-group">
                    <label>{{ __('app.tek_laporan_foto') }} <span style="color:#ef4444;">*</span></label>
                    <div id="uploadArea" class="laporan-upload-area">
                        <div id="uploadPlaceholder" style="display:block;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"
                                 fill="none" viewBox="0 0 24 24" stroke="#94a3b8"
                                 style="margin:0 auto 8px; display:block;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0
                                         011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0
                                         01-2-2V9z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <p style="color:#64748b; font-size:13px; margin:0;">{{ __('app.tek_laporan_foto_click') }}</p>
                        </div>
                        <div id="imagePreview" style="display:none;">
                            <img src="" alt="Preview"
                                style="width:100%; height:200px; border-radius:8px; object-fit:cover;">
                            <button type="button" id="removeImage"
                                    style="margin-top:8px; display:block; margin-left:auto; margin-right:auto;
                                           background:rgba(239,68,68,0.1); color:#ef4444;
                                           border:1px solid rgba(239,68,68,0.3); padding:5px 12px;
                                           border-radius:6px; cursor:pointer; font-size:12px;">
                                {{ __('app.tek_laporan_foto_remove') }}
                            </button>
                        </div>
                    </div>
                    <input type="file" id="foto" name="foto" accept="image/*" style="display:none;">
                    @error('foto')<span class="error">{{ $message }}</span>@enderror
                </div>

                <div class="laporan-btn-group">
                    <a href="{{ route('teknisi.dashboard') }}" class="laporan-btn-batal">{{ __('app.tek_laporan_batal') }}</a>
                    <button type="submit" class="laporan-btn-submit">{{ __('app.tek_laporan_submit') }}</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/app.js') }}"></script>
@endpush