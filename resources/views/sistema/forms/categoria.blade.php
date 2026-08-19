

<label for="">Categoria Geral</label>
<select name="categoria_geral_id" class="form-control" required>
    @foreach ($categoria_geral as $ctg)
    <option value="{{$ctg->id}}">{{$ctg->categoria_geral}}</option>
@endforeach
</select>
<label for="">Subcategoria</label>
<input type="text" name="categoria" class="form-control" placeholder=" Subcategoria" value="{{ $categoria->categoria ?? old('categoria') }}" required>
<button type="submit" class="btn btn-primary">Salvar</button>
