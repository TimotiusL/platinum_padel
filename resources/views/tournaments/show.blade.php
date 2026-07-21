@extends('layouts.app')

@section('title','Tournaments Detail')

@section('content')

<a href="{{ route('tournaments.index') }}" class="back-link">Kembali ke Tournaments</a>
<div class="t-banner">
    <div class="poster-lg" style="background:${t.poster}"></div>
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

    <button class="tab-btn">
        Fixture
    </button>

    <button class="tab-btn active">
        Results
    </button>

    <button class="tab-btn">
        Leaderboard
    </button>

    <button class="tab-btn">
        Bracket
    </button>

</div>

<div id="tabContent">

    <div class="acc open">

        <div class="acc-head">

            <span>Group Stage</span>

            <span class="count">

                12 Match

            </span>

        </div>

        <div class="acc-body">

            <div class="m-card">

                <div class="m-header">

                    MEN · GROUP A

                </div>

                <div class="m-team">

                    <div class="names">

                        Michelle / Alice

                    </div>

                    <div class="score">

                        6

                    </div>

                </div>

                <div class="m-divider"></div>

                <div class="m-team winner">

                    <div class="names">

                        Kevin / Budi

                    </div>

                    <div class="score">

                        7

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection