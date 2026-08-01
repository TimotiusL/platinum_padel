-- Jalankan satu kali pada database platinum_padel lama.
-- Aman dijalankan kembali pada MariaDB/XAMPP karena memakai IF NOT EXISTS.

ALTER TABLE `tournament_matches`
    ADD COLUMN IF NOT EXISTS `bracket_order` INT UNSIGNED NULL AFTER `round`,
    ADD COLUMN IF NOT EXISTS `score_team_a` TINYINT UNSIGNED NOT NULL DEFAULT 0 AFTER `status`,
    ADD COLUMN IF NOT EXISTS `score_team_b` TINYINT UNSIGNED NOT NULL DEFAULT 0 AFTER `score_team_a`;

CREATE INDEX IF NOT EXISTS `tm_category_round_order_idx`
    ON `tournament_matches` (`category_id`, `round`, `bracket_order`);

ALTER TABLE `teams`
    ADD COLUMN IF NOT EXISTS `played` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `updated_at`,
    ADD COLUMN IF NOT EXISTS `win` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `played`,
    ADD COLUMN IF NOT EXISTS `lose` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `win`,
    ADD COLUMN IF NOT EXISTS `points` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `lose`,
    ADD COLUMN IF NOT EXISTS `score_for` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `points`,
    ADD COLUMN IF NOT EXISTS `score_against` INT UNSIGNED NOT NULL DEFAULT 0 AFTER `score_for`;
