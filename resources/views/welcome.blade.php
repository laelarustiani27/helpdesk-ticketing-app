@extends('layouts.app')

@section('title', 'NetRespond - Home')

@section('content')
<div class="welcome-header">
    <h1>Welcome to NetRespond</h1>
    <p>Real-time Network Issue Monitoring System</p>
</div>

<div class="stats">
    <div class="stat-card">
        <h3>Critical Issues</h3>
        <div class="number critical">{{ $criticalTickets }}</div>
        <p>Requires immediate attention</p>
    </div>
    
    <div class="stat-card">
        <h3>Warning Issues</h3>
        <div class="number warning">{{ $warningTickets }}</div>
        <p>Needs monitoring</p>
    </div>
    
    <div class="stat-card">
        <h3>Resolved Issues</h3>
        <div class="number resolved">{{ $resolvedTickets }}</div>
        <p>Successfully fixed</p>
    </div>
    
    <div class="stat-card">
        <h3>Total Tickets</h3>
        <div class="number total">{{ $totalTickets }}</div>
        <p>All time tickets</p>
    </div>
</div>

@if($tickets->count() > 0)
<div class="tickets-section">
    <h2>Recent Network Issues</h2>
    
    @foreach($tickets as $ticket)
    <div class="ticket-item">
        <h3>{{ $ticket->title }}</h3>
        <p>{{ Str::limit($ticket->description, 150) }}</p>
        
        <div style="margin-top: 10px;">
            <span class="badge badge-{{ $ticket->status }}">
                {{ strtoupper($ticket->status) }}
            </span>
            <span class="badge badge-priority">
                Priority: {{ strtoupper($ticket->priority) }}
            </span>
        </div>
        
        <small style="color: #999; display: block; margin-top: 10px;">
            📍 {{ $ticket->location }} • 
            👤 {{ $ticket->reported_by }} • 
            🕐 {{ $ticket->reported_at->diffForHumans() }}
        </small>
    </div>
    @endforeach
</div>
@else
<div class="tickets-section" style="text-align: center; padding: 60px;">
    <h2 style="color: #27ae60;">🎉 No Active Issues!</h2>
    <p style="color: #666; margin-top: 10px;">All systems are running smoothly.</p>
</div>
@endif
@endsection