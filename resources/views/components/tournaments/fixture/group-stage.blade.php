<div class="acc open">

    <div class="acc-head">

        <span>Group Stage</span>

        <span class="count">
            12 Matches
        </span>

    </div>

    <div class="acc-body">

        {{-- Group A --}}
        <div class="sub-title">
            Group A
        </div>

        @include('components.tournaments.fixture.match-card')
        @include('components.tournaments.fixture.match-card')

        {{-- Group B --}}
        <div class="sub-title">
            Group B
        </div>

        @include('components.tournaments.fixture.match-card')
        @include('components.tournaments.fixture.match-card')

    </div>

</div>