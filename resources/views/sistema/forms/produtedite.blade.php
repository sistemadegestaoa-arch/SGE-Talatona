<label for="">Descrição</label>
<input type="text" name="produto" class="form-control"  required value="{{$Products->produto}}">
<label for="">Apresentação</label>
<input type="text" name="apresentacao" class="form-control"  required value="{{$Products->apresentacao}}">
<label for="">Categoria</label>
<select name="categoria_id" class="form-control" required>
@foreach ($Ct as $categoria)
    @if($categoria->id==$Products->categoria_id)
    <option value="{{$categoria->id}}">{{$categoria->categoria}}</option>
    @endif
    @endforeach
    <option></option>
    @foreach ($Ct as $categoria)
    <option value="{{$categoria->id}}">{{$categoria->categoria}}</option>
@endforeach
</select>

<label for="">Lote</label>
<select name="lote_id" class="form-control" >
@foreach ($Lote as $lote)
    @if($lote->id==$Products->lote_id)
    <option value="{{$lote->id}}">{{$lote->lote}}</option>
    @endif
    @endforeach
    <option></option>
    @foreach ($Lote as $lote)
    <option value="{{$lote->id}}">{{$lote->lote}}</option>
@endforeach
</select>

<label for=""> Codigo de Barra</label>
<input type="text" name="codigo" class="form-control"  value="{{$Products->codigo}}">
<label for=""> Quantidade</label>
<input type="text" name="quantidade" class="form-control" required value="{{$Products->quantidade}}">
<label for=""> Estock Minimo</label>
<input type="text" name="stokminimo" class="form-control" required value="{{$Products->stokminimo}}">


<button type="submit" class="btn btn-primary">Salvar</button>
