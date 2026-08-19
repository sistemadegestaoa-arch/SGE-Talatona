@extends('louyout.app')
@section('conteodo')
@include('louyout.styles')

<div class="page-header-bar">
    <div>
        <h4 class="page-title"><i class="feather icon-plus-circle"></i> Novo Fornecedor</h4>
    </div>
    <a href="{{ route('fornecedor.index') }}" class="btn-back"><i class="feather icon-arrow-left"></i> Voltar</a>
</div>

<div class="form-card">
    <form action="{{ route('createfornecedor.store') }}" method="post">
        @csrf
        <div class="form-row-2">
            <div class="fg">
                <label>Nome</label>
                <input type="text" name="fornecedor" class="fc" placeholder="Nome do fornecedor" required>
            </div>
            <div class="fg">
                <label>Telefone</label>
                <input type="text" name="telefone" class="fc" placeholder="Ex: 923 000 000" required>
            </div>
        </div>
        <div class="form-row-2">
            <div class="fg">
                <label>NIF</label>
                <input type="text" name="nif" class="fc" placeholder="Número de identificação fiscal" required>
            </div>
            <div class="fg">
                <label>Email</label>
                <input type="email" name="email" class="fc" placeholder="email@exemplo.com" required>
            </div>
        </div>
        <div class="fg">
            <label>Endereço</label>
            <textarea name="endereco" class="fc" rows="3" placeholder="Endereço completo" required></textarea>
        </div>
        <button type="submit" class="btn-save"><i class="feather icon-save"></i> Salvar</button>
    </form>
</div>
@endsection
