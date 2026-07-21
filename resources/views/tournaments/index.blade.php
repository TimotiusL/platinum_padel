@extends('layouts.app')

@section('title','Tournaments')

@section('content')

<div class="section">
    <div class="section-head">
        <div>
            <div class="eyebrow">All Events</div>
            <h2>Tournaments</h2>
        </div>
    </div>
    <div class="t-grid">
        <a href="{{ route('tournaments.show', ['id' => 1]) }}" class="t-card" style="display:flex;">
            <div class="poster" style="background:linear-gradient(160deg,#173a2e,#0c1e17);">
                <span class="status open">Open</span>
                <span class="badge">Open Tournament</span>
            </div>
            <div class="body">
                <div class="name">
                    Platinum Padel Open 2026
                </div>
                <div class="meta">
                    <div class="meta-row">
                        📅 20 - 22 Agustus 2026
                    </div>
                    <div class="meta-row">
                        🎾 Platinum Padel — Court A
                    </div>
                    <div class="meta-row">
                        📍 Surabaya
                    </div>
                </div>
                <div class="tags">
                    <span class="tag">Men Double</span>
                    <span class="tag">Beginner</span>
                    <span class="tag">Open</span>
                </div>
            </div>
        </a>
    </div>
</div>


<div class="section">
    <div class="section-head">
        <div>
            <div class="eyebrow">Archive</div>
            <h2>History</h2>
        </div>
    </div>
    <div class="h-grid">
        <div class="t-card">
                <div class="poster" style="background:linear-gradient(160deg,#173a2e,#0c1e17);">
                    <span class="badge">Platinum Series</span>
                    <span class="status finished">Finished</span>
                </div>
                <div class="body">
                    <div class="name">
                        Platinum Padel Championship 2025
                    </div>
                    <div class="meta">
                        <div class="meta-row">
                            📅 15 - 17 November 2025
                        </div>
                        <div class="meta-row">
                            🎾 Platinum Padel Surabaya
                        </div>
                    </div>
                    <div class="tags">
                        <span class="tag">Men Double</span>
                        <span class="tag">Intermediate</span>
                    </div>
                </div>
        </div>
    </div>
</div>

@endsection