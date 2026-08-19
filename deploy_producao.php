<?php
/**
 * ╔═══════════════════════════════════════════════════════════════════╗
 * ║  SCRIPT DE DEPLOY PARA PRODUÇÃO — KIFICA                         ║
 * ║  Execute: php deploy_producao.php                                 ║
 * ║                                                                   ║
 * ║  O que faz:                                                       ║
 * ║  1. Faz backup do banco de dados                                  ║
 * ║  2. Regista as migrations antigas como "já executadas"            ║
 * ║  3. Corre a migration de deploy seguro                            ║
 * ║  4. Limpa caches                                                  ║
 * ╚═══════════════════════════════════════════════════════════════════╝
 *
 * APAGAR ESTE FICHEIRO APÓS O DEPLOY!
 */

// ── Verificar que é chamado via CLI ──────────────────────────────────────────
if (php_sapi_name() !== 'cli') {
    die("Este script só pode ser executado via linha de comandos.\n");
}

echo "\n";
echo "╔═══════════════════════════════════════════════════════╗\n";
echo "║         DEPLOY KIFICA — MODO SEGURO                   ║\n";
echo "╚═══════════════════════════════════════════════════════╝\n\n";

// ── Ler credenciais do .env ───────────────────────────────────────────────────
$env = file_get_contents(__DIR__ . '/.env');
preg_match('/DB_HOST=(.+)/',     $env, $m); $host   = trim($m[1] ?? 'localhost');
preg_match('/DB_DATABASE=(.+)/', $env, $m); $db     = trim($m[1] ?? '');
preg_match('/DB_USERNAME=(.+)/', $env, $m); $user   = trim($m[1] ?? 'root');
preg_match('/DB_PASSWORD=(.*)/', $env, $m); $pass   = trim($m[1] ?? '');
preg_match('/DB_PORT=(.+)/',     $env, $m); $port   = trim($m[1] ?? '3306');

echo "📋 Base de dados: {$db} @ {$host}:{$port}\n\n";

// ── 1. BACKUP ─────────────────────────────────────────────────────────────────
$backupDir  = __DIR__ . '/storage/backups';
if (!is_dir($backupDir)) mkdir($backupDir, 0755, true);

$backupFile = $backupDir . '/backup_pre_deploy_' . date('Y-m-d_H-i-s') . '.sql';
$passArg    = $pass ? "-p\"$pass\"" : '';

echo "💾 [1/4] A criar backup em:\n   {$backupFile}\n";

$dumpCmd = "mysqldump -h{$host} -P{$port} -u{$user} {$passArg} {$db} > \"{$backupFile}\" 2>&1";

// Em Windows com XAMPP, o mysqldump pode estar em caminhos diferentes
$mysqldumpPaths = [
    'mysqldump',                                    // PATH normal
    'C:\\xampp\\mysql\\bin\\mysqldump',             // XAMPP Windows
    '/Applications/XAMPP/bin/mysqldump',            // XAMPP Mac
    '/usr/bin/mysqldump',                           // Linux
];
foreach ($mysqldumpPaths as $bin) {
    if (@exec("{$bin} --version 2>&1") !== '') {
        $dumpCmd = "\"{$bin}\" -h{$host} -P{$port} -u{$user} {$passArg} {$db} > \"{$backupFile}\" 2>&1";
        break;
    }
}
exec($dumpCmd, $out, $ret);

if ($ret !== 0 || !file_exists($backupFile) || filesize($backupFile) < 100) {
    echo "⚠️  Backup falhou ou arquivo está vazio.\n";
    echo "   Verifique se mysqldump está instalado e nas variáveis de ambiente.\n";
    echo "   Deseja continuar SEM backup? (s/N): ";
    $resposta = strtolower(trim(fgets(STDIN)));
    if ($resposta !== 's') {
        echo "❌ Deploy cancelado.\n\n";
        exit(1);
    }
} else {
    $size = round(filesize($backupFile) / 1024, 1);
    echo "   ✅ Backup criado ({$size} KB)\n\n";
}

// ── 2. LIGAR À BASE DE DADOS ──────────────────────────────────────────────────
try {
    $pdo = new PDO("mysql:host={$host};port={$port};dbname={$db};charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    echo "❌ Erro ao ligar à base de dados: " . $e->getMessage() . "\n\n";
    exit(1);
}

// ── 3. SINCRONIZAR TABELA DE MIGRATIONS ──────────────────────────────────────
echo "🔧 [2/4] A sincronizar tabela de migrations...\n";

// Garante que a tabela migrations existe e tem auto_increment correcto
$pdo->exec("CREATE TABLE IF NOT EXISTS `migrations` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `migration` varchar(255) NOT NULL,
    `batch` int(11) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

$pdo->exec("ALTER TABLE migrations MODIFY id INT UNSIGNED NOT NULL AUTO_INCREMENT");

// Migrations que já existem em produção (tabelas já existentes)
// Marcamos como batch 1 para o Laravel não tentar recriá-las
$migracoesProdExistentes = [
    '2014_10_12_000000_create_users_table',
    '2014_10_12_100000_create_password_resets_table',
    '2019_08_19_000000_create_failed_jobs_table',
    '2026_05_20_000001_create_atendimento_table',
    '2026_05_31_000001_remove_departamento_id_from_produto_table',
    '2026_06_06_000001_create_paciente_table',
    '2026_06_06_000002_create_episodio_table',
    '2026_06_06_000003_create_triagem_table',
    '2026_06_06_000004_create_consulta_table',
    '2026_06_06_000005_create_pedido_exame_table',
    '2026_06_06_000006_create_resultado_exame_table',
    '2026_06_06_000007_create_receita_table',
    '2026_06_06_000008_add_receita_id_to_atendimento_table',
    '2026_06_14_000001_add_bloqueado_to_produto_table',
    '2026_06_15_000001_create_requisicao_farmaco_table',
    '2026_06_15_000002_add_urgente_to_episodio_table',
    '2026_06_15_000003_add_senha_chamada_to_episodio_table',
    '2026_06_15_000004_create_prescricao_table',
];

$stmt = $pdo->prepare("SELECT COUNT(*) FROM migrations WHERE migration = ?");
$ins  = $pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, 1)");

$batch1Count = 0;
foreach ($migracoesProdExistentes as $m) {
    $stmt->execute([$m]);
    if ($stmt->fetchColumn() == 0) {
        $ins->execute([$m]);
        $batch1Count++;
    }
}
echo "   ✅ {$batch1Count} migration(s) marcadas como já executadas\n\n";

// ── 4. CORRER A MIGRATION DE DEPLOY SEGURO ───────────────────────────────────
echo "🚀 [3/4] A executar migration de deploy seguro...\n\n";

$cmd = "php artisan migrate --path=database/migrations/2026_06_15_000005_deploy_producao_seguro.php --force 2>&1";
passthru($cmd, $ret);

if ($ret !== 0) {
    echo "\n❌ Migration falhou. Verifique os erros acima.\n";
    echo "   O backup está em: {$backupFile}\n\n";
    exit(1);
}

// ── 5. LIMPAR CACHES ──────────────────────────────────────────────────────────
echo "\n🧹 [4/4] A limpar caches...\n";
passthru("php artisan cache:clear 2>&1");
passthru("php artisan view:clear 2>&1");
passthru("php artisan route:clear 2>&1");
passthru("php artisan config:clear 2>&1");

echo "\n";
echo "╔═══════════════════════════════════════════════════════╗\n";
echo "║  ✅ DEPLOY CONCLUÍDO COM SUCESSO                       ║\n";
echo "╠═══════════════════════════════════════════════════════╣\n";
echo "║  Backup guardado em:                                   ║\n";
echo "║  storage/backups/                                      ║\n";
echo "╠═══════════════════════════════════════════════════════╣\n";
echo "║  ⚠️  IMPORTANTE:                                       ║\n";
echo "║  Apague este ficheiro do servidor:                     ║\n";
echo "║  php deploy_producao.php  →  DEL deploy_producao.php  ║\n";
echo "╚═══════════════════════════════════════════════════════╝\n\n";
