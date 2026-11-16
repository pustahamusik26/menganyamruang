<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$pdo = Illuminate\Support\Facades\DB::connection()->getPdo();
$tables = ['users', 'password_reset_tokens', 'sessions', 'cache', 'cache_locks', 'cultural_sites', 'documentary_videos', 'team_members'];

$sql = "-- SQL Dump Arsip Budaya (menganyamruang.id)\n";
$sql .= "SET NAMES utf8mb4;\n";
$sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

foreach ($tables as $t) {
    try {
        $res = $pdo->query("SHOW CREATE TABLE `{$t}`")->fetch(PDO::FETCH_ASSOC);
    } catch (\Throwable $e) {
        continue;
    }
    $create = $res['Create Table'];
    $sql .= "DROP TABLE IF EXISTS `{$t}`;\n" . $create . ";\n\n";

    if (in_array($t, ['users', 'cultural_sites', 'documentary_videos', 'team_members'], true)) {
        $rows = $pdo->query("SELECT * FROM `{$t}`")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($rows as $row) {
            $cols = array_map(fn ($c) => "`{$c}`", array_keys($row));
            $vals = array_map(fn ($v) => $v === null ? 'NULL' : $pdo->quote((string) $v), array_values($row));
            $sql .= "INSERT INTO `{$t}` (" . implode(', ', $cols) . ") VALUES (" . implode(', ', $vals) . ");\n";
        }
        $sql .= "\n";
    }
}

$sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";

$outFile = __DIR__ . '/../database/arsip_budaya_full.sql';
file_put_contents($outFile, $sql);
echo "Generated {$outFile} (" . strlen($sql) . " bytes)\n";
