<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$w = []; $ok = [];

// 1. Lotes órfãos
$r = DB::table('lote')->leftJoin('produto','produto.id','=','lote.produto_id')->whereNull('produto.id')->count();
$r ? $w[] = "$r lote(s) com produto_id inválido" : $ok[] = "Nenhum lote órfão";

// 2. Stock negativo
$r = DB::table('estoque')->select('produto_id','departamento_id',DB::raw('SUM(entrada)-SUM(saida) as stock'))->groupBy('produto_id','departamento_id')->havingRaw('SUM(entrada)-SUM(saida) < 0')->count();
$r ? $w[] = "$r produto(s)/dep com stock negativo" : $ok[] = "Nenhum stock negativo";

// 3. Lotes expirados com stock > 0
$lotes = DB::table('lote')->join('produto','produto.id','=','lote.produto_id')->whereNotNull('lote.validade')->whereRaw("lote.validade < CURDATE() AND lote.validade > '0100-01-01'")->select('lote.id as lote_id')->get();
$exp = 0;
foreach ($lotes as $l) { if (DB::table('estoque')->where('lote_id',$l->lote_id)->sum(DB::raw('entrada-saida')) > 0) $exp++; }
$exp ? $w[] = "$exp lote(s) expirado(s) com stock > 0" : $ok[] = "Nenhum lote expirado com stock positivo";

// 4. fornecedor_id com nomes de departamentos
$depNomes = DB::table('departamento')->pluck('departamento')->toArray();
$r = DB::table('estoque')->whereIn('fornecedor_id',$depNomes)->count();
$r ? $w[] = "$r movimento(s) com fornecedor_id = nome de departamento" : $ok[] = "Nenhum fornecedor_id inválido";

// 5. Subcategorias órfãs
$r = DB::table('categoria')->leftJoin('categoria_geral','categoria_geral.id','=','categoria.categoria_geral_id')->whereNull('categoria_geral.id')->count();
$r ? $w[] = "$r subcategoria(s) sem categoria geral" : $ok[] = "Nenhuma subcategoria órfã";

echo "\n=== VERIFICAÇÃO FINAL ===\n\n";
echo "✅ OK (" . count($ok) . "):\n";
foreach ($ok as $m) echo "   $m\n";
echo "\n⚠️  AVISOS (" . count($w) . "):\n";
empty($w) ? print("   Nenhum.\n") : array_map(fn($m) => print("   $m\n"), $w);
echo "\n========================\n";
