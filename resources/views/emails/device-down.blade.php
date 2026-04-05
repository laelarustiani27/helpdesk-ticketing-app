@component('mail::message')
# Perangkat Jaringan Down!

Perangkat berikut terdeteksi **tidak dapat dijangkau**:

@component('mail::panel')
**Nama Perangkat:** {{ $device->nama }}

**IP Address:** {{ $device->ip_address }}

**Lokasi:** {{ $device->lokasi ?? '-' }}

**Waktu Down:** {{ now()->format('d/m/Y H:i:s') }}
@endcomponent

Segera lakukan pengecekan dan penanganan!

@component('mail::button', ['url' => config('app.url'), 'color' => 'red'])
Buka Dashboard
@endcomponent

Terima kasih,
{{ config('app.name') }}
@endcomponent


<x-mail::message>
# Introduction

The body of your message.

<x-mail::button :url="''">
Button Text
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
