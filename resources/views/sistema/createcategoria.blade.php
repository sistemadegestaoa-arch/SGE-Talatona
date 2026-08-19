@extends('louyout.app')
@section('conteodo')
@include('louyout.styles')

<div class="page-header-bar">
    <div>
        <h4 class="page-title"><i class="feather icon-plus-circle"></i> Nova Categoria</h4>
    </div>
    <a href="{{ route('categoria.index') }}" class="btn-back"><i class="feather icon-arrow-left"></i> Voltar</a>
</div>

@if(isset($sms))
    <div class="alert-err">✗ {{ $sms }}</div>
@endif

<div class="form-card">
    <form action="{{ route('createcategoria.store') }}" method="post">
        @csrf
        <div class="fg">
            <label>Categoria Geral</label>
            <select name="categoria_geral_id" class="fc" required>
                @foreach ($categoria_geral as $ctg)
                    <option value="{{ $ctg->id }}">{{ $ctg->categoria_geral }}</option>
                @endforeach
            </select>
        </div>
        <div class="fg">
            <label>Subcategoria</label>
            <input type="text" name="categoria" class="fc" placeholder="Nome da subcategoria" required value="{{ old('categoria') }}">
        </div>
        <button type="submit" class="btn-save"><i class="feather icon-save"></i> Salvar</button>
    </form>
</div>
@endsection
