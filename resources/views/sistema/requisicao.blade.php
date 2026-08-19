@extends('louyout.app')

@section('conteodo')
<br>
<h3> Requisção de Medicamentos</h3>
<h3 style="background: lightgreen;color: #ffffff;">{{$sms ?? ''}}</h3>
   
<br>
<div class="container">
<a href="{{route('lerrequisicao.show')}}" class="btn btn-primary" style="float: right"> Voltar</a>
    
   

    
                
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"> CENTRO DE REFERÊNCIA DO KIFICA, FAÇA A SUA REQISIÇÃO</h3>
                    </div>
                    <form action="{{route('requisicao.crearrequisicao')}}" method="post" class="form-group">
                        @csrf
                        <div class="panel-body">
                        <textarea name="requisicao" id="content"  class="form-control ckeditor"></textarea>
                            <input type="text" name="user_id" value="{{auth::user()->id}}" hidden>
                            <input type="text" name="departamento_id" value="{{auth::user()->departamento_id}}" hidden>
                           
                        </div>
                    <button type="submit" class="btn btn-primary">Enviar</button>
                    </form>
                </div>
                
      

</div>
@endsection
