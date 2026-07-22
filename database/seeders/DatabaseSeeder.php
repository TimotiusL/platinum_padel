<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Player;
use App\Models\Tournament;
use App\Models\Team;
use App\Models\MatchTournament;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Seed players from your hardcoded data
        $players = [
            ['name' => 'Pemain 1', 'location' => 'Jakarta Selatan, DKI Jakarta', 'titles' => 2, 'rank' => 1],
            ['name' => 'Pemain 2', 'location' => '', 'titles' => 1, 'rank' => 2],
            ['name' => 'Pemain 3', 'location' => '', 'titles' => 1, 'rank' => 3],
            ['name' => 'Pemain 4', 'location' => '', 'titles' => 1, 'rank' => 4],
            ['name' => 'Pemain 5', 'location' => '', 'titles' => 1, 'rank' => 5],
            ['name' => 'Pemain 6', 'location' => 'Kota Bandung, Jawa Barat', 'titles' => 1, 'rank' => 6],
            ['name' => 'Pemain 7', 'location' => 'Jakarta Selatan, DKI Jakarta', 'titles' => 1, 'rank' => 7],
            ['name' => 'Pemain 8', 'location' => 'Jakarta Selatan, DKI Jakarta', 'titles' => 1, 'rank' => 8],
            ['name' => 'Farrel Suhandi', 'location' => 'Jakarta', 'titles' => 0, 'rank' => 9],
            ['name' => 'Kelvin Susanto', 'location' => 'Jakarta', 'titles' => 0, 'rank' => 10],
            ['name' => 'Alexander Wibowo', 'location' => 'Jakarta', 'titles' => 2, 'rank' => 11],
            ['name' => 'Marcus Halim', 'location' => 'Jakarta', 'titles' => 1, 'rank' => 12],
            ['name' => 'Reza Hartono', 'location' => 'Jakarta', 'titles' => 0, 'rank' => 13],
            ['name' => 'Vincent Tanuwijaya', 'location' => 'Jakarta', 'titles' => 0, 'rank' => 14],
            ['name' => 'Christopher Nathan', 'location' => 'Jakarta', 'titles' => 0, 'rank' => 15],
            ['name' => 'Imran Wicaksono', 'location' => 'Jakarta', 'titles' => 0, 'rank' => 16],
        ];

        foreach ($players as $playerData) {
            Player::create($playerData);
        }

        // Create tournament
        $tournament = Tournament::create([
            'name' => 'Platinum Grand Opening Padel Tournament',
            'badge' => 'PLATINUM SOCIETAS',
            'start_date' => Carbon::parse('2026-07-18 08:00:00'),
            'end_date' => Carbon::parse('2026-07-20 20:00:00'),
            'venue' => 'Platinum Padel Court',
            'venue_sub' => 'Senayan City Lt.3',
            'location' => 'Jakarta Selatan, DKI Jakarta',
            'prize' => '150.000.000++',
            'status' => 'ongoing',
            'poster' => 'linear-gradient(160deg,#173a2e,#0c1e17)',
            'tags' => ['Rookie · Men', 'Bronze · Men', 'Upper Beginner · Women', 'Open · Men', 'Open · Women'],
        ]);

        // Create teams
        $teamConfigs = [
            ['code' => 'A1', 'players' => ['Farrel Suhandi', 'Kelvin Susanto'], 'group' => 'A'],
            ['code' => 'A2', 'players' => ['Alexander Wibowo', 'Marcus Halim'], 'group' => 'A'],
            ['code' => 'A3', 'players' => ['Reza Hartono', 'Vincent Tanuwijaya'], 'group' => 'A'],
            ['code' => 'A4', 'players' => ['Pemain 3', 'Pemain 4'], 'group' => 'A'],
            ['code' => 'B1', 'players' => ['Christopher Nathan', 'Imran Wicaksono'], 'group' => 'B'],
            ['code' => 'B2', 'players' => ['Pemain 5', 'Pemain 6'], 'group' => 'B'],
            ['code' => 'B3', 'players' => ['Pemain 7', 'Pemain 8'], 'group' => 'B'],
            ['code' => 'B4', 'players' => ['Pemain 1', 'Pemain 2'], 'group' => 'B'],
        ];

        $teams = [];
        foreach ($teamConfigs as $config) {
            $p1 = Player::where('name', $config['players'][0])->first();
            $p2 = Player::where('name', $config['players'][1])->first();
            if ($p1 && $p2) {
                $teams[$config['code']] = Team::create([
                    'code' => $config['code'],
                    'player1_id' => $p1->id,
                    'player2_id' => $p2->id,
                    'tournament_id' => $tournament->id,
                    'group_code' => $config['group'],
                    'points' => 0,
                ]);
            }
        }

        // Create matches
        $matches = [
            // Group A matches
            ['t1' => 'A1', 't2' => 'A2', 's1' => 2, 's2' => 6, 'stage' => 'group_stage', 'time' => '2026-07-18 09:00:00', 'winner' => 'A2'],
            ['t1' => 'A3', 't2' => 'A4', 's1' => 4, 's2' => 6, 'stage' => 'group_stage', 'time' => '2026-07-18 09:30:00', 'winner' => 'A4'],
            ['t1' => 'A1', 't2' => 'A3', 's1' => 6, 's2' => 3, 'stage' => 'group_stage', 'time' => '2026-07-18 10:00:00', 'winner' => 'A1'],
            ['t1' => 'A2', 't2' => 'A4', 's1' => 6, 's2' => 4, 'stage' => 'group_stage', 'time' => '2026-07-18 10:30:00', 'winner' => 'A2'],
            ['t1' => 'A1', 't2' => 'A4', 's1' => 3, 's2' => 6, 'stage' => 'group_stage', 'time' => '2026-07-18 11:00:00', 'winner' => 'A4'],
            ['t1' => 'A2', 't2' => 'A3', 's1' => 6, 's2' => 2, 'stage' => 'group_stage', 'time' => '2026-07-18 11:30:00', 'winner' => 'A2'],
            // Group B matches
            ['t1' => 'B1', 't2' => 'B2', 's1' => 6, 's2' => 1, 'stage' => 'group_stage', 'time' => '2026-07-18 09:00:00', 'winner' => 'B1'],
            ['t1' => 'B3', 't2' => 'B4', 's1' => 4, 's2' => 6, 'stage' => 'group_stage', 'time' => '2026-07-18 09:30:00', 'winner' => 'B4'],
            ['t1' => 'B1', 't2' => 'B3', 's1' => 6, 's2' => 2, 'stage' => 'group_stage', 'time' => '2026-07-18 10:00:00', 'winner' => 'B1'],
            ['t1' => 'B2', 't2' => 'B4', 's1' => 3, 's2' => 6, 'stage' => 'group_stage', 'time' => '2026-07-18 10:30:00', 'winner' => 'B4'],
            ['t1' => 'B1', 't2' => 'B4', 's1' => 4, 's2' => 6, 'stage' => 'group_stage', 'time' => '2026-07-18 11:00:00', 'winner' => 'B4'],
            ['t1' => 'B2', 't2' => 'B3', 's1' => 6, 's2' => 5, 'stage' => 'group_stage', 'time' => '2026-07-18 11:30:00', 'winner' => 'B2'],
            // R16
            ['t1' => 'A2', 't2' => 'B2', 's1' => 6, 's2' => 1, 'stage' => 'r16', 'time' => '2026-07-19 14:00:00', 'winner' => 'A2'],
            ['t1' => 'B1', 't2' => 'A3', 's1' => 6, 's2' => 4, 'stage' => 'r16', 'time' => '2026-07-19 14:30:00', 'winner' => 'B1'],
            // QF
            ['t1' => 'A2', 't2' => 'B1', 's1' => 6, 's2' => 2, 'stage' => 'qf', 'time' => '2026-07-19 16:00:00', 'winner' => 'A2'],
            // SF
            ['t1' => 'A2', 't2' => 'A3', 's1' => 6, 's2' => 3, 'stage' => 'sf', 'time' => '2026-07-20 09:00:00', 'winner' => 'A2'],
            // Final
            ['t1' => 'A2', 't2' => 'B3', 's1' => 6, 's2' => 2, 'stage' => 'final', 'time' => '2026-07-20 15:00:00', 'winner' => 'A2'],
        ];

        foreach ($matches as $matchData) {
            $t1 = $teams[$matchData['t1']] ?? null;
            $t2 = $teams[$matchData['t2']] ?? null;
            $winner = isset($matchData['winner']) ? $teams[$matchData['winner']] : null;

            if ($t1 && $t2) {
                MatchTournament::create([
                    'tournament_id' => $tournament->id,
                    'team1_id' => $t1->id,
                    'team2_id' => $t2->id,
                    'score1' => $matchData['s1'],
                    'score2' => $matchData['s2'],
                    'winner_id' => $winner ? $winner->id : null,
                    'stage' => $matchData['stage'],
                    'scheduled_at' => Carbon::parse($matchData['time']),
                    'played_at' => Carbon::parse($matchData['time']),
                ]);
            }
        }
    }
}