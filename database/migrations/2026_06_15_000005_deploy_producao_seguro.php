<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * ╔═══════════════════════════════════════════════════════════════════╗
 * ║  MIGRATION DE DEPLOY SEGURO PARA PRODUÇÃO                        ║
 * ║  Cria tabelas/colunas apenas se NÃO existirem.                   ║
 * ║  Preserva todos os dados existentes.                             ║
 * ╚═══════════════════════════════════════════════════════════════════╝
 */
class DeployProducaoSeguro extends Migration
{
    public function up()
    {
        // ── 1. TABELA: atendimento ─────────────────────────────────────────
        if (!Schema::hasTable('atendimento')) {
            Schema::create('atendimento', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedInteger('requisicao_id')->nullable();
                $table->string('utente');
                $table->string('processo')->nullable();
                $table->unsignedInteger('departamento_id');
                $table->unsignedBigInteger('users_id');
                $table->text('observacao')->nullable();
                $table->date('data');
                $table->unsignedBigInteger('receita_id')->nullable();
                $table->timestamps();
            });
            $this->log('✓ Criada tabela atendimento');
        } else {
            // Adicionar coluna receita_id se não existir
            if (!Schema::hasColumn('atendimento', 'receita_id')) {
                Schema::table('atendimento', function (Blueprint $table) {
                    $table->unsignedBigInteger('receita_id')->nullable()->after('data');
                });
                $this->log('✓ Adicionada coluna atendimento.receita_id');
            }
        }

        // ── 2. TABELA: paciente ────────────────────────────────────────────
        if (!Schema::hasTable('paciente')) {
            Schema::create('paciente', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('nome');
                $table->enum('sexo', ['M', 'F']);
                $table->date('data_nascimento')->nullable();
                $table->string('numero_processo')->nullable()->unique();
                $table->string('telefone')->nullable();
                $table->string('morada')->nullable();
                $table->timestamps();
            });
            $this->log('✓ Criada tabela paciente');
        }

        // ── 3. TABELA: episodio ────────────────────────────────────────────
        if (!Schema::hasTable('episodio')) {
            Schema::create('episodio', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('paciente_id');
                $table->unsignedBigInteger('triagem_user_id');
                $table->date('data');
                $table->enum('estado', ['em_espera','em_consulta','aguarda_exame','concluido'])->default('em_espera');
                $table->boolean('urgente')->default(false);
                $table->string('senha', 10)->nullable();
                $table->timestamp('chamado_em')->nullable();
                $table->timestamps();
                $table->unique(['paciente_id', 'data']);
            });
            $this->log('✓ Criada tabela episodio');
        } else {
            // Adicionar colunas novas se não existirem
            $cols = ['urgente' => 'boolean', 'senha' => 'string', 'chamado_em' => 'timestamp'];
            foreach ($cols as $col => $type) {
                if (!Schema::hasColumn('episodio', $col)) {
                    Schema::table('episodio', function (Blueprint $table) use ($col) {
                        if ($col === 'urgente') {
                            $table->boolean('urgente')->default(false)->after('estado');
                        } elseif ($col === 'senha') {
                            $table->string('senha', 10)->nullable()->after('urgente');
                        } elseif ($col === 'chamado_em') {
                            $table->timestamp('chamado_em')->nullable()->after('senha');
                        }
                    });
                    $this->log("✓ Adicionada coluna episodio.{$col}");
                }
            }
        }

        // ── 4. TABELA: triagem ─────────────────────────────────────────────
        if (!Schema::hasTable('triagem')) {
            Schema::create('triagem', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('episodio_id')->unique();
                $table->string('pressao_arterial')->nullable();
                $table->decimal('temperatura', 4, 1)->nullable();
                $table->decimal('peso', 6, 2)->nullable();
                $table->decimal('altura', 5, 1)->nullable();
                $table->unsignedSmallInteger('frequencia_cardiaca')->nullable();
                $table->unsignedSmallInteger('frequencia_respiratoria')->nullable();
                $table->unsignedSmallInteger('saturacao_oxigenio')->nullable();
                $table->text('observacao')->nullable();
                $table->timestamps();
            });
            $this->log('✓ Criada tabela triagem');
        }

        // ── 5. TABELA: consulta ────────────────────────────────────────────
        if (!Schema::hasTable('consulta')) {
            Schema::create('consulta', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('episodio_id')->unique();
                $table->unsignedBigInteger('medico_id');
                $table->text('diagnostico')->nullable();
                $table->text('observacao')->nullable();
                $table->date('data');
                $table->timestamps();
            });
            $this->log('✓ Criada tabela consulta');
        }

        // ── 6. TABELA: pedido_exame ────────────────────────────────────────
        if (!Schema::hasTable('pedido_exame')) {
            Schema::create('pedido_exame', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('consulta_id');
                $table->unsignedBigInteger('medico_id');
                $table->string('descricao_exame');
                $table->boolean('urgente')->default(false);
                $table->enum('estado', ['pendente','concluido'])->default('pendente');
                $table->text('observacao')->nullable();
                $table->date('data_pedido');
                $table->timestamps();
            });
            $this->log('✓ Criada tabela pedido_exame');
        }

        // ── 7. TABELA: resultado_exame ─────────────────────────────────────
        if (!Schema::hasTable('resultado_exame')) {
            Schema::create('resultado_exame', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('pedido_exame_id')->unique();
                $table->unsignedBigInteger('tecnico_id');
                $table->text('resultado');
                $table->string('ficheiro_path')->nullable();
                $table->date('data_resultado');
                $table->timestamps();
            });
            $this->log('✓ Criada tabela resultado_exame');
        }

        // ── 8. TABELA: receita ─────────────────────────────────────────────
        if (!Schema::hasTable('receita')) {
            Schema::create('receita', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('consulta_id');
                $table->unsignedBigInteger('medico_id');
                $table->enum('estado', ['pendente','dispensada'])->default('pendente');
                $table->text('observacao')->nullable();
                $table->date('data');
                $table->timestamps();
            });
            $this->log('✓ Criada tabela receita');
        }

        // ── 9. TABELA: receita_item ────────────────────────────────────────
        if (!Schema::hasTable('receita_item')) {
            Schema::create('receita_item', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('receita_id');
                $table->unsignedInteger('produto_id');
                $table->string('dose')->nullable();
                $table->string('frequencia')->nullable();
                $table->string('duracao')->nullable();
                $table->integer('quantidade')->default(1);
                $table->timestamps();
            });
            $this->log('✓ Criada tabela receita_item');
        }

        // ── 10. COLUNAS: produto.bloqueado ─────────────────────────────────
        $bloqueadoCols = ['bloqueado', 'motivo_bloqueio', 'bloqueado_por', 'bloqueado_em'];
        $faltam = array_filter($bloqueadoCols, fn($c) => !Schema::hasColumn('produto', $c));
        if (!empty($faltam)) {
            Schema::table('produto', function (Blueprint $table) use ($faltam) {
                if (in_array('bloqueado', $faltam))
                    $table->boolean('bloqueado')->default(false)->after('stokminimo');
                if (in_array('motivo_bloqueio', $faltam))
                    $table->string('motivo_bloqueio')->nullable()->after('bloqueado');
                if (in_array('bloqueado_por', $faltam))
                    $table->unsignedBigInteger('bloqueado_por')->nullable()->after('motivo_bloqueio');
                if (in_array('bloqueado_em', $faltam))
                    $table->timestamp('bloqueado_em')->nullable()->after('bloqueado_por');
            });
            $this->log('✓ Adicionadas colunas bloqueio à tabela produto');
        }

        // ── 11. TABELA: requisicao_farmaco ─────────────────────────────────
        if (!Schema::hasTable('requisicao_farmaco')) {
            Schema::create('requisicao_farmaco', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('departamento_id');
                $table->unsignedBigInteger('solicitante_id');
                $table->enum('estado', ['pendente','atendida','rejeitada'])->default('pendente');
                $table->text('observacao')->nullable();
                $table->unsignedBigInteger('atendido_por')->nullable();
                $table->timestamp('atendido_em')->nullable();
                $table->timestamps();
            });
            $this->log('✓ Criada tabela requisicao_farmaco');
        }

        if (!Schema::hasTable('requisicao_farmaco_item')) {
            Schema::create('requisicao_farmaco_item', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('requisicao_farmaco_id');
                $table->unsignedInteger('produto_id');
                $table->integer('quantidade')->default(1);
                $table->string('observacao_item')->nullable();
                $table->timestamps();
                $table->foreign('requisicao_farmaco_id')
                      ->references('id')->on('requisicao_farmaco')->onDelete('cascade');
            });
            $this->log('✓ Criada tabela requisicao_farmaco_item');
        }

        // ── 12. TABELA: prescricao ─────────────────────────────────────────
        if (!Schema::hasTable('prescricao')) {
            Schema::create('prescricao', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('consulta_id');
                $table->unsignedBigInteger('medico_id');
                $table->text('diagnostico')->nullable();
                $table->text('observacao')->nullable();
                $table->date('data');
                $table->timestamps();
            });
            $this->log('✓ Criada tabela prescricao');
        }

        if (!Schema::hasTable('prescricao_item')) {
            Schema::create('prescricao_item', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->unsignedBigInteger('prescricao_id');
                $table->string('medicamento');
                $table->string('forma_farmaceutica')->nullable();
                $table->string('dosagem')->nullable();
                $table->string('dose')->nullable();
                $table->string('frequencia')->nullable();
                $table->string('duracao')->nullable();
                $table->integer('quantidade')->default(1);
                $table->text('instrucoes')->nullable();
                $table->timestamps();
                $table->foreign('prescricao_id')
                      ->references('id')->on('prescricao')->onDelete('cascade');
            });
            $this->log('✓ Criada tabela prescricao_item');
        }

        $this->log('✅ Deploy concluído — todos os dados existentes preservados.');
    }

    public function down()
    {
        // O down() é intencional mente vazio — não apagar dados em produção
        // Se precisar reverter, fazer manualmente com backup
    }

    private function log(string $msg): void
    {
        // Escreve no output do artisan migrate
        echo "    {$msg}" . PHP_EOL;
    }
}
