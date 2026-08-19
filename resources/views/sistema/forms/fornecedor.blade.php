<label for="">Nome</label>
<input type="text" name="fornecedor" class="form-control" value="{{ $categoria->categoria ?? old('categoria') }}"  required>
<label for="">Telefone</label>
<input type="text" name="telefone" class="form-control" value="{{ $categoria->categoria ?? old('categoria') }}" required>
<label for="">Nif</label>
<input type="text" name="nif" class="form-control" value="{{ $categoria->categoria ?? old('categoria') }}" required>
<label for="">Email</label>
<input type="text" name="email" class="form-control" value="{{ $categoria->categoria ?? old('categoria') }}" required>
<label for="">Endereco</label>
<textarea name="endereco" id="" cols="30" rows="4" class="form-control" value="{{ $categoria->categoria ?? old('categoria') }}" required></textarea>
<button type="submit" class="btn btn-primary">Salvar</button>