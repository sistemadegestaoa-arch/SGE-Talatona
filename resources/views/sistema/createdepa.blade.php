@extends('louyout.app')
@section('conteodo')
@include('louyout.styles')

<div class="page-header-bar">
    <div>
        <h4 class="page-title"><i class="feather icon-plus-circle"></i> Novo Departamento</h4>
    </div>
    <a href="{{ route('departamento.index') }}" class="btn-back"><i class="feather icon-arrow-left"></i> Voltar</a>
</div>

<div class="form-card">
    <form action="{{ route('createdepa.store') }}" method="post">
        @csrf
        <div class="fg">
            <label>Nome do Departamento</label>
            <input type="text" name="departamento" class="fc" placeholder="Ex: Farmácia" required value="{{ old('departamento') }}">
        </div>
        <button type="submit" class="btn-save"><i class="feather icon-save"></i> Salvar</button>
    </form>
</div>
@endsection
