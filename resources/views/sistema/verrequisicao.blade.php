

@extends('louyout.app')
@section('conteodo')
<br>
<h3>Requisições</h3>
<h3 style="background: greenyellow;color:#fff"> {{$sucesso ?? ''}}</h3>
<a href="{{route('requisicao.crearrequisicao')}}" class="btn btn-primary" style="float: right" >Nova</a>
<br>
<div class="container">
    @foreach ($Requi as $requisicao)
                @if ($requisicao->statos=='Pendente')
    <div style="width: 500px;height: auto; background-color: #ccc;color:#000;border-radius: 10px;box-shadow:2px 0px 2px #000; ">
        <h3 style="color: #fff"> {{ $requisicao->departamento}}</h3>
        <span>Funcionario: {{ $requisicao->name}}</span>
        <p style="background-color: #fff;color:#000"> {!! $requisicao->requisicao!!}</p> 
<a href="{{route('updaterequi.updaterequi',$requisicao->id)}}" class="btn btn-danger" >{{$requisicao->statos}}</a>
<a href="{{ route('atendimento.create', ['requisicao_id'=>$requisicao->id]) }}" class="btn btn-success" style="margin-left:6px;">
    <i class="feather icon-user-check"></i> Atender
</a>
       
      
    </div><br>
    @endif
    @endforeach
    {{$Requi->links()}}
</div>


@endsection

