<label for="">Lote</label>
<input type="text" name="lote" class="form-control" value="{{ $lote->lote ?? old('lote') }}"  required>
<label for="">Validade</label>
<input type="date" name="validade" class="form-control" value="{{ $lote->validade ?? old('validade') }}" required>
<input type="hidden" name="departamento_id" value="{{auth::user()->departamento_id}}">

<label for="">Codigo de Barra</label>
<input type="text" name="codigo_barra" class="form-control" value="{{ $lote->codigo_barra ?? old('cogigo_barra') }}" >
<label for=""> Produto</label>


<select name="produto_id" class="form-control">
    @foreach ($pro as $produto)
    @if ($produto->departamento_id== auth::user()->departamento_id)
    <option value="{{$produto->id}}">{{$produto->produto}} </option>
    @endif

@endforeach
</select>
<button type="submit" class="btn btn-primary">Salvar</button>


