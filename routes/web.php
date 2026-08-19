<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rota pública para criação do administrador inicial (sem login)
Route::middleware('admin.exists')->group(function () {
    Route::get('setup',  'RegisterController@setupView')->name('setup.index');
    Route::post('setup', 'RegisterController@setupStore')->name('setup.store');
});

Route::get('sair', 'HomeController@sair')->name('sair');

// ── PAINEL DE CHAMADAS — público, sem login ───────────────────────────────────
Route::get('painel-chamadas',       'ChamadaController@painel')->name('chamadas.painel');
Route::get('painel-chamadas/sse',   'ChamadaController@stream')->name('chamadas.sse');
Route::get('api/fila-espera',       'ChamadaController@filaEspera')->name('chamadas.fila');

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/', 'HomeController@index')->name('home.index');

    // Rechamar paciente — médico pode chamar novamente
    Route::post('chamadas/{id}/rechamar', 'ChamadaController@rechamar')->name('chamadas.rechamar');

    // Departamentos
    Route::get('createdepartamento',    'DepartamentoConroller@create')->name('createdepartamento.create');
    Route::post('createdepa',           'DepartamentoConroller@store')->name('createdepa.store');
    Route::get('departamento',          'DepartamentoConroller@index')->name('departamento.index');
    Route::get('alterardepa/{id}',      'DepartamentoConroller@edit')->name('alterardepa.edit');
    Route::put('updatedepa/{id}',       'DepartamentoConroller@update')->name('updatedepa.update');

    // Lotes
    Route::get('lote',              'LoteController@create')->name('lote.create');
    Route::post('lote',             'LoteController@store')->name('lote.store');
    Route::get('ver-lotes',         'LoteController@index')->name('ver-lotes.index');
    Route::get('editarlote/{id}',   'LoteController@edit')->name('editarlote.edit');
    Route::put('updatelote/{id}',   'LoteController@update')->name('updatelote.update');

    // Categorias
    Route::get('categoria',             'CategoriaController@index')->name('categoria.index');
    Route::get('createcategoria',       'CategoriaController@create')->name('createcategoria.create');
    Route::post('createcategoria',      'CategoriaController@store')->name('createcategoria.store');
    Route::put('updatecategoria/{id}',  'CategoriaController@update')->name('updatecategoria.update');
    Route::post('categoria-geral',      'CategoriaController@storeGeral')->name('categoria-geral.store');
    Route::put('categoria-geral/{id}',  'CategoriaController@updateGeral')->name('categoria-geral.update');

    // Fornecedores
    Route::get('fornecedor',            'FornecedorController@index')->name('fornecedor.index');
    Route::get('createfornecedor',      'FornecedorController@create')->name('createfornecedor.create');
    Route::post('createfornecedor',     'FornecedorController@store')->name('createfornecedor.store');

    // Utilizadores
    Route::get('registar',          'RegisterController@index')->name('registar.index');
    Route::get('createusuario',     'RegisterController@registar')->name('createusuario.registar');
    Route::get('verusuario',        'RegisterController@show')->name('verusuario.show');
    Route::post('createusuario',    'RegisterController@create')->name('createusuario.create');

    // Produtos
    Route::get('get-state-list',        'ProdutoController@getStateList');
    Route::get('produto',               'ProdutoController@verp')->name('produto.verp');
    Route::get('createproduto',         'ProdutoController@registar')->name('createproduto.registar');
    Route::post('createproduto',        'ProdutoController@create')->name('createproduto.create');
    Route::get('produtedite/{id}',      'ProdutoController@produtedite')->name('produtedite.produtedite');
    Route::put('produtoupdate/{id}',    'ProdutoController@produtoupdate')->name('produtoupdate.produtoupdate');
    Route::get('detalhes_produto/{id}', 'ProdutoController@detalhes_produto')->name('detalhes_produto.detalhes_produto');
    Route::get('alerte',                'ProdutoController@alert')->name('alerte.alert');

    // Estoque
    Route::get('estoque/{id}',  'EstoqueController@estoque')->name('estoque.estoque');
    Route::post('estoque',      'EstoqueController@create')->name('estoque.create');

    // Requisições
    Route::get('requisicao',            'ProdutoController@requisicao')->name('requisicao.requisicao');
    Route::post('requisicao',           'ProdutoController@crearrequisicao')->name('requisicao.crearrequisicao');
    Route::get('lerrequisicao',         'ProdutoController@show')->name('lerrequisicao.show');
    Route::get('verrequisicao',         'ProdutoController@showr')->name('verrequisicao.showr');
    Route::get('updaterequi/{id}',      'ProdutoController@updaterequi')->name('updaterequi.updaterequi');

    // Relatórios
    Route::get('relatorio',                 'RelatorioController@index')->name('relatorio.index');
    Route::post('pdf',                      'RealtoeioController@relatorio')->name('pdf.relatorio');
    Route::get('pdf',                       'RealtoeioController@index')->name('pdf.îndex');
    Route::post('relatoriofornecedor',      'RealtoeioController@relatoriofornecedor')->name('relatoriofornecedor.relatoriofornecedor');
    Route::post('relatoriotipo',            'RealtoeioController@relatoriotipo')->name('relatoriotipo.relatoriotipo');
    Route::get('relatorioproduto',          'RealtoeioController@relatorioproduto')->name('relatorioproduto.relatorioproduto');
    Route::post('guia',                     'RealtoeioController@pdf')->name('guia.pdf');
    Route::post('dia',                      'RealtoeioController@dia')->name('dia.dia');
    Route::get('ficha/{id}',               'RealtoeioController@ficha')->name('ficha.ficha');
    Route::post('fichalote',               'RealtoeioController@fichalote')->name('fichalote.fichalote');
    Route::post('fichastok',               'RealtoeioController@fichaestok')->name('fichastok.fichaestok');
    Route::get('expirados',                'RealtoeioController@expirados')->name('expirados.expirados');
    Route::get('estoqueminimo',            'RealtoeioController@estoqueminimo')->name('estoqueminimo.estoqueminimo');

    // Relatórios Hospitalares
    Route::get('relatorio-hospitalar',                      'RelatorioHospitalarController@index')->name('relatorio.hospitalar');
    Route::post('relatorio-hospitalar/atendimentos',        'RelatorioHospitalarController@atendimentosPacientes')->name('relatorio.atendimentos');
    Route::post('relatorio-hospitalar/por-data',            'RelatorioHospitalarController@atendimentosPorData')->name('relatorio.por-data');
    Route::post('relatorio-hospitalar/por-funcionario',     'RelatorioHospitalarController@atendimentosPorFuncionario')->name('relatorio.por-funcionario');
    Route::post('relatorio-hospitalar/medico',              'RelatorioHospitalarController@relatorioMedico')->name('relatorio.medico');
    Route::post('relatorio-hospitalar/laboratorio',         'RelatorioHospitalarController@relatorioLaboratorio')->name('relatorio.laboratorio');
    Route::post('relatorio-hospitalar/requisicoes-farmaco',  'RelatorioHospitalarController@relatorioRequisicoesFarmaco')->name('relatorio.requisicoes-farmaco');
    // Perfil
    Route::get('perfil',            'PerfilController@index')->name('perfil.index');
    Route::post('perfil/nome',      'PerfilController@updateNome')->name('perfil.nome');
    Route::post('perfil/senha',     'PerfilController@updateSenha')->name('perfil.senha');

    // Notificações — AJAX polling (fallback)
    Route::get('api/notificacoes',  'NotificacaoController@index')->name('notificacoes.index');

    // Notificações — SSE tempo real
    Route::get('api/sse',           'SseController@stream')->name('sse.stream');

    // ── MÓDULO ENFERMEIRO / S.O. ──────────────────────────────────────────────
    Route::middleware('so.enfermeiro')->group(function () {
        Route::get('enfermeiro', 'EnfermeiroController@index')->name('enfermeiro.index');
        Route::get('enfermeiro/requisicaolist', 'EnfermeiroController@listAll')->name('enfermeirorequisicao.index');
        // Requisições de fármacos — reutiliza o mesmo controller do laboratório
        Route::post('enfermeiro/requisicao',            'RequisicaoFarmacoController@store')->name('enfermeiro.requisicao.store');
        Route::get('enfermeiro/requisicao/{id}/editar', 'RequisicaoFarmacoController@edit')->name('enfermeiro.requisicao.edit');
        Route::put('enfermeiro/requisicao/{id}',        'RequisicaoFarmacoController@update')->name('enfermeiro.requisicao.update');
        Route::get('enfermeiro/requisicao/{id}/pdf',    'RequisicaoFarmacoController@pdf')->name('enfermeiro.requisicao.pdf');
    });

    // Requisição de Fármacos — Laboratório
    Route::middleware('so.laboratorio')->group(function () {
        Route::get('requisicao-farmaco',            'RequisicaoFarmacoController@index')->name('requisicao-farmaco.index');
        Route::post('requisicao-farmaco',           'RequisicaoFarmacoController@store')->name('requisicao-farmaco.store');
        Route::get('requisicao-farmaco/{id}/editar','RequisicaoFarmacoController@edit')->name('requisicao-farmaco.edit');
        Route::put('requisicao-farmaco/{id}',       'RequisicaoFarmacoController@update')->name('requisicao-farmaco.update');
    });

    // PDF — acessível por laboratório e farmácia (só auth)
    Route::get('requisicao-farmaco/{id}/pdf', 'RequisicaoFarmacoController@pdf')->name('requisicao-farmaco.pdf');

    // Requisição de Fármacos — Farmácia (receber e atender)
    Route::middleware('so.farmacia')->group(function () {
        Route::get('requisicao-farmaco-farmacia',           'RequisicaoFarmacoController@farmaciaIndex')->name('requisicao-farmaco.farmacia');
        Route::post('requisicao-farmaco/{id}/atender',      'RequisicaoFarmacoController@atender')->name('requisicao-farmaco.atender');
    });

    // Atendimento — restrito à Farmácia
    Route::middleware('so.farmacia')->group(function () {
        Route::get('atendimento',           'AtendimentoController@index')->name('atendimento.index');
        Route::get('atendimento/novo',      'AtendimentoController@create')->name('atendimento.create');
        Route::post('atendimento',          'AtendimentoController@store')->name('atendimento.store');
        Route::get('atendimento/{id}',      'AtendimentoController@show')->name('atendimento.show');
        Route::get('atendimento/{id}/pdf',  'AtendimentoController@pdf')->name('atendimento.pdf');
        Route::get('atendimento-relatorio', 'AtendimentoController@relatorio')->name('atendimento.relatorio');

        // Receitas médicas — Fase 5
        Route::get('receitas-pendentes',            'ReceitaController@index')->name('receitas.index');
        Route::get('receitas/{id}',                 'ReceitaController@show')->name('receitas.show');
        Route::post('receitas/{id}/dispensar',      'ReceitaController@dispensar')->name('receitas.dispensar');
        Route::get('receitas/{id}/pdf',             'ReceitaController@pdf')->name('receitas.pdf');
    });

    // ── MÓDULO HOSPITALAR ─────────────────────────────────────────────────────

    // Triagem — restrito a Catalogação / Consultas Externas
    Route::middleware('so.triagem')->group(function () {
        Route::get('triagem',               'TriagemController@index')->name('triagem.index');
        Route::get('triagem/nova',          'TriagemController@create')->name('triagem.create');
        Route::get('triagem/pesquisar',     'TriagemController@pesquisarPaciente')->name('triagem.pesquisar');
        Route::get('triagem/estatisticas',  'TriagemController@estatisticas')->name('triagem.estatisticas');
        Route::post('triagem',              'TriagemController@store')->name('triagem.store');
        Route::get('triagem/{id}',          'TriagemController@show')->name('triagem.show');
    });

    // Consulta Médica — restrito a departamentos médicos
    Route::middleware('so.medico')->group(function () {
        Route::get('consultas',                             'ConsultaController@index')->name('consultas.index');
        Route::get('consultas/{id}',                        'ConsultaController@show')->name('consultas.show');
        Route::post('consultas/{id}/diagnostico',           'ConsultaController@storeDiagnostico')->name('consultas.diagnostico');
        Route::post('consultas/{id}/exame',                 'ConsultaController@storePedidoExame')->name('consultas.exame');
        Route::post('consultas/{id}/receita',               'ConsultaController@storeReceita')->name('consultas.receita');
        Route::get('consultas/{id}/concluir',               'ConsultaController@concluir')->name('consultas.concluir');
        Route::get('consultas/{id}/receita/pdf',            'ConsultaController@receitaPdf')->name('consultas.receita.pdf');
        Route::get('consultas/{id}/pedido-exame/pdf',       'ConsultaController@pedidoExamePdf')->name('consultas.pedido-exame.pdf');
        // Prescrição médica
        Route::post('consultas/{id}/prescricao',            'PrescricaoController@store')->name('consultas.prescricao.store');
        Route::get('consultas/{id}/prescricao/pdf',         'PrescricaoController@pdf')->name('consultas.prescricao.pdf');
    });

    // Laboratório — restrito ao departamento de Laboratório
    Route::middleware('so.laboratorio')->group(function () {
        Route::get('laboratorio',                   'LaboratorioController@index')->name('laboratorio.index');
        Route::get('laboratorio/{id}',              'LaboratorioController@show')->name('laboratorio.show');
        Route::post('laboratorio/{id}/resultado',   'LaboratorioController@store')->name('laboratorio.store');
    });

    // Bloqueio de Fármacos
    Route::get('produto-bloqueio',              'ProdutoBloqueioController@index')->name('produto-bloqueio.index');
    Route::post('produto-bloqueio/{id}/bloquear',   'ProdutoBloqueioController@bloquear')->name('produto-bloqueio.bloquear');
    Route::post('produto-bloqueio/{id}/desbloquear','ProdutoBloqueioController@desbloquear')->name('produto-bloqueio.desbloquear');

    // ── ELIMINAÇÕES — só admin do Armazém Central ──────────────────────────
    Route::middleware('so.armazem')->group(function () {
        Route::DELETE('apagardepa/{id}',        'DepartamentoConroller@destroy')->name('apagardepa.destroy');
        Route::DELETE('apagarcategoria/{id}',   'CategoriaController@destroy')->name('apagarcategoria.destroy');
        Route::DELETE('categoria-geral/{id}',   'CategoriaController@destroyGeral')->name('categoria-geral.destroy');
        Route::DELETE('apagarfornecedor/{id}',  'FornecedorController@destroy')->name('apagarfornecedor.destroy');
        Route::DELETE('apagaruser/{id}',        'RegisterController@destroy')->name('apagaruser.destroy');
        Route::DELETE('apagarproduto/{id}',     'ProdutoController@destroy')->name('apagarproduto.destroy');
        Route::DELETE('apagarlote/{id}',        'LoteController@destroy')->name('apagarlote.destroy');
    });
});

Auth::routes(['register' => false]);
