@extends('layouts.app')

@section('title','Home')

@section('content')

<section class="hero">
    <div class="watermark"></div>
    <div class="hero-inner">
        <div class="kicker">Platinum Societas presents</div>
        <h1>Home of Padel<br><em>Players &amp; Tournaments</em></h1>
        <p>Where every rally is recorded, every rank is earned, and every champion joins the Platinum roster.</p>
        <button onclick="window.location='{{ route('register') }}'" class="cta-gold">Daftar sebagai Player &nbsp;→</button>
    </div>
</section>

<section class="section">
    <div class="section-head">
        <div>
            <div class="eyebrow">
                Currently Live
            </div>
            <h2>
                Tournaments
            </h2>
        </div>
        <a
            class="see-all"
            href="{{ route('tournaments.index') }}">
            View All →
        </a>
    </div>
    <div class="t-grid">
        @include('components.tournament-card')
    </div>
</section>

<section class="section">
    <div class="section-head">
        <div>
            <div class="eyebrow">
                Latest Champions
            </div>
            <h2>
                Finalist Pool
            </h2>
        </div>
    </div>
    <div class="f-grid">
        @include('components.finalist-card')

        @include('components.finalist-card')

        @include('components.finalist-card')

        @include('components.finalist-card')
    </div>
</section>

<section class="section">
    <div class="section-head">
        <div>
            <div class="eyebrow">
                Archive
            </div>
            <h2>
                History
            </h2>
        </div>
        <span class="see-all">
            3 Tournaments
        </span>
    </div>
    <div class="h-grid">

        @include('components.history-card')

        @include('components.history-card')

        @include('components.history-card')

    </div>
</section>

@endsection