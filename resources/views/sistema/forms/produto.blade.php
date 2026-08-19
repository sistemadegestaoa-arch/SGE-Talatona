<label for="">Categoria Geral</label>
<select id="especialidade" name="setor_id" class="form-control" required>
    @foreach ($Cageral as $geral)
    <option value="{{$geral->id}}">{{$geral->categoria_geral}}</option>
@endforeach

</select>
<label for="">Sub Categoria</label>
<select name="categoria_id" id="categoria_id" class="form-control" required>

</select>



<label for="">Descrição</label>
<input type="text" name="produto" class="form-control"  required>
<label for="">Apresentação</label>
<input type="text" name="apresentacao" class="form-control"  required>
<label for="">Codigo de Barra</label>
<input type="text" name="codigo" class="form-control" >

@php
$users = DB::table('departamento')
->join('users', 'departamento.id', '=', 'users.departamento_id')
->select('users.*','departamento.departamento')
->get();
@endphp
@foreach ($users as $usuario)
    @if ($usuario->id==auth::user()->id)
        @php
            $departamento_id=$usuario->departamento_id;
        @endphp
    @endif
@endforeach
<input type="text" name="departamento_id" class="form-control" value="{{$departamento_id}}" hidden>
<label for=""> Quantidade</label>
<input type="text" name="quantidade" class="form-control" required>
<label for=""> Estock Minimo</label>
<input type="text" name="stokminimo" class="form-control" required>

<label for=""> Data de Aquisação</label>
<input type="date" name="data_aquisicao" class="form-control" >


<button type="submit" class="btn btn-primary">Salvar</button>



