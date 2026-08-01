@php
    $isEdit = isset($match);
    $selectedCategory = old('category_id', $isEdit ? $match->category_id : $selectedCategoryId);
    $selectedTeamA = old('team_a_id', $isEdit ? $match->team_a_id : '');
    $selectedTeamB = old('team_b_id', $isEdit ? $match->team_b_id : '');
@endphp

@if($errors->any())
    <div class="notice error">
        @foreach($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="grid">
    <label>
        Category
        <select id="category_id" name="category_id" required>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected($selectedCategory == $category->id)>
                    {{ $category->tournament?->title }} — {{ $category->name }}
                </option>
            @endforeach
        </select>
    </label>

    <label>
        Round
        <select name="round" required>
            @foreach(['group' => 'Group Stage', 'r16' => 'Round of 16', 'qf' => 'Quarter Final', 'sf' => 'Semi Final', 'final' => 'Final'] as $value => $label)
                <option value="{{ $value }}" @selected(old('round', $isEdit ? strtolower($match->round) : 'group') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </label>

    <label>
        Team A
        <select id="team_a_id" name="team_a_id" required>
            <option value="">Pilih Team A</option>
            @foreach($categories as $category)
                @foreach($category->teams as $team)
                    <option value="{{ $team->id }}" data-category="{{ $category->id }}" @selected($selectedTeamA == $team->id)>
                        {{ $team->team_code }} — {{ $team->team_name }}
                    </option>
                @endforeach
            @endforeach
        </select>
    </label>

    <label>
        Team B
        <select id="team_b_id" name="team_b_id" required>
            <option value="">Pilih Team B</option>
            @foreach($categories as $category)
                @foreach($category->teams as $team)
                    <option value="{{ $team->id }}" data-category="{{ $category->id }}" @selected($selectedTeamB == $team->id)>
                        {{ $team->team_code }} — {{ $team->team_name }}
                    </option>
                @endforeach
            @endforeach
        </select>
    </label>

    <label>
        Bracket Order
        <input type="number" name="bracket_order" min="1"
               value="{{ old('bracket_order', $isEdit ? $match->bracket_order : '') }}"
               placeholder="Otomatis bila kosong">
    </label>

    <label>
        Court
        <input type="text" name="court"
               value="{{ old('court', $isEdit ? $match->court : '') }}"
               placeholder="Contoh: Court 1">
    </label>

    <label>
        Match Date
        <input type="datetime-local" name="match_date"
               value="{{ old('match_date', $isEdit && $match->match_date ? $match->match_date->format('Y-m-d\TH:i') : '') }}">
    </label>

    <label>
        Status
        <select name="status" required>
            @foreach(['scheduled' => 'Scheduled', 'ongoing' => 'Ongoing', 'finished' => 'Finished'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $isEdit ? $match->status : 'scheduled') === $value)>{{ $label }}</option>
            @endforeach
        </select>
    </label>
</div>

<div class="actions">
    <button class="btn" type="submit">{{ $isEdit ? 'Update Match' : 'Create Match' }}</button>
    <a class="btn secondary" href="{{ route('matches.index', ['category_id' => $selectedCategory]) }}">Back</a>
</div>

<script>
    function filterTeams() {
        const category = document.getElementById('category_id').value;

        ['team_a_id', 'team_b_id'].forEach(id => {
            const select = document.getElementById(id);
            [...select.options].forEach(option => {
                if (!option.dataset.category) return;
                option.hidden = option.dataset.category !== category;
                option.disabled = option.dataset.category !== category;
            });

            const selected = select.options[select.selectedIndex];
            if (selected && selected.dataset.category && selected.dataset.category !== category) {
                select.value = '';
            }
        });
    }

    document.getElementById('category_id').addEventListener('change', filterTeams);
    filterTeams();
</script>
