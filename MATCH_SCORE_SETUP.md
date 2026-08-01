# Live Match, Scoreboard, Bracket, Leaderboard

## Database baru

Import:

```text
platinum_padel_live_updated.sql
```

File tersebut sudah memiliki kolom:

- `tournament_matches.score_team_a`
- `tournament_matches.score_team_b`
- `tournament_matches.bracket_order`
- `teams.played`
- `teams.win`
- `teams.lose`
- `teams.points`
- `teams.score_for`
- `teams.score_against`

## Database lama yang sudah berisi data

Jalankan salah satu:

```bash
php artisan migrate --path=database/migrations/2026_07_31_231700_add_live_match_bracket_columns.php
```

atau import:

```text
database/live_match_upgrade.sql
```

Jangan menjalankan `migrate:fresh`.

## Akses admin

```text
/admin/login
/admin/dashboard
/admin/matches
/admin/scoreboard
/admin/categories
```

Password admin:

```text
platinum2026
```

## Alur penggunaan

1. Buka `Categories`.
2. Klik `Generate Group` untuk membuat round-robin berdasarkan `group_code`, atau klik `+ Add Match` untuk membuat match manual.
3. Setelah group stage selesai, klik `Generate Bracket`.
4. Buka `Matches`, lalu klik `Score`.
5. `Save Live Score` menyimpan skor dan mengubah status menjadi `ongoing`.
6. `Finish Match` menentukan winner dan otomatis mengisi pertandingan ronde berikutnya.
7. Halaman tournament user melakukan refresh data otomatis setiap 3 detik.
8. Leaderboard dipisahkan per kategori dan diurutkan berdasarkan total score yang dicetak.
