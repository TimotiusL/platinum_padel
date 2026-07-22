@extends('layouts.app')

@section('title','Tournaments Detail')

@section('content')

<a href="{{ route('tournaments.index') }}" class="back-link">< Kembali ke Tournaments</a>
<div class="t-banner">
    <div class="poster-lg" sstyle="background: linear-gradient(160deg,#173a2e,#0c1e17);"></div>
    <div class="info">
        <div class="badge">
            | Open Tournament
        </div>

        <h1>
            Platinum Padel Open 2026
        </h1>

        <div class="row">
            📅 20 - 22 Agustus 2026
        </div>

        <div class="row">
            🎾 Platinum Padel Surabaya
        </div>

        <div class="row">
            📍 Surabaya
        </div>

        <a href="#" class="cta-gold" style="width:fit-content; margin-top:10px;">
            Daftar Sekarang →
        </a>
    </div>
</div>

<div style="display:flex; align-items:center; justify-content:space-between; margin-top:34px;">
    <div class="eyebrow dim">
        Pilih Kategori
    </div>
</div>

<div class="cat-row">

    <button class="cat-btn active">
        🏆 Open Men
        <span class="t">— Beginner</span>
    </button>

    <button class="cat-btn">
        🏆 Open Women
        <span class="t">— Beginner</span>
    </button>

    <button class="cat-btn">
        🏆 Mixed Double
        <span class="t">— Intermediate</span>
    </button>

</div>

<div class="tab-row">

    <button type="button" class="tab-btn active">
        Fixture
    </button>

    <button class="tab-btn">
        Results
    </button>

    <button class="tab-btn">
        Leaderboard
    </button>

    <button class="tab-btn">
        Bracket
    </button>

</div>




@endsection