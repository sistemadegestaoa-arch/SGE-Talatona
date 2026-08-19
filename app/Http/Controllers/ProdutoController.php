<?php

namespace App\Http\Controllers;

use App\Categoria;
use App\Fornecedor;
use App\Produto;
use App\requisicao;
use App\categoria_geral;
use App\lote;
use App\Departamento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Http\Controllers\Traits\VerificaPermissaoEliminar;

class ProdutoController extends Controller
{
    use VerificaPermissaoEliminar;
    public function __construct(categoria_geral $categoria_geral, Categoria $categoria)
    {
        $this->categoria_geral = $categoria_geral;
        $this->categoria       = $categoria;
    }

    /**
     * Formulário de criação de produto.
     * Produtos são globais — sem departamento_id.
     */
    public function registar()
    {
        $categoria_geral = $this->categoria_geral->orderby('categoria_geral', 'ASC')->get();
        $categoria       = $this->categoria->where('id', '=', 0)->orderby('categoria', 'ASC')->get();

        return view('sistema.createproduto', ['Ct' => $categoria, 'Cageral' => $categoria_geral]);
    }

    /**
     * Detalhe de um produto com os seus lotes.
     * Mostra lotes do departamento do utilizador.
     */
    public function detalhes_produto($id)
    {
        $depa      = Departamento::all();
        $fornecedor = Fornecedor::all();

        $produto = DB::table('produto')
            ->join('categoria', 'produto.categoria_id', '=', 'categoria.id')
            ->join('lote', 'produto.id', '=', 'lote.produto_id')
            ->select(
                'produto.produto', 'produto.apresentacao', 'produto.codigo',
                'produto.quantidade', 'produto.stokminimo', 'produto.data_aquisicao',
                'categoria.categoria', 'lote.*'
            )
            ->where('produto.id', '=', $id)
            ->where('lote.departamento_id', '=', auth()->user()->departamento_id)
            ->get();

        return view('sistema.produto_detalhe', [
            'Products' => $produto,
            'Fr'       => $fornecedor,
            'id'       => $id,
            'Dp'       => $depa,
        ]);
    }

    /**
     * AJAX — carrega subcategorias por categoria geral.
     */
    public function getStateList(Request $request)
    {
        $categoria = Categoria::where('categoria_geral_id', $request->setor_id)->get();
        return response()->json($categoria);
    }

    /**
     * Lista de requisições (admin).
     */
    public function showr()
    {
        $requisicao1 = DB::table('departamento')
            ->join('requisicao', 'departamento.id', '=', 'requisicao.departamento_id')
            ->join('users', 'requisicao.users_id', '=', 'users.id')
            ->select('departamento.departamento', 'requisicao.*', 'users.name')
            ->paginate();

        return view('sistema.verrequisicao', ['Requi' => $requisicao1]);
    }

    /**
     * Lista de produtos com stock real calculado por departamento do utilizador.
     */
    public function verp()
    {
        $fornecedor = Fornecedor::all();
        $depId      = auth()->user()->departamento_id;

        $produto = DB::table('produto')
            ->join('categoria', 'produto.categoria_id', '=', 'categoria.id')
            ->select('produto.*', 'categoria.categoria')
            ->orderBy('produto.produto')
            ->get();

        // Stock real por produto, filtrado pelo departamento do utilizador
        $stocks = DB::table('estoque')
            ->select(
                'produto_id',
                DB::raw('SUM(entrada) as total_entrada'),
                DB::raw('SUM(saida) as total_saida')
            )
            ->where('departamento_id', $depId)
            ->groupBy('produto_id')
            ->get()
            ->keyBy('produto_id');

        foreach ($produto as $p) {
            $mov          = $stocks->get($p->id);
            $p->stock_real = $mov ? ($mov->total_entrada - $mov->total_saida) : 0;
        }

        return view('sistema.produto', ['Products' => $produto, 'Fr' => $fornecedor]);
    }

    /**
     * Formulário de edição de produto.
     */
    public function produtedite($id)
    {
        $produto         = Produto::findOrFail($id);
        $categoria_geral = $this->categoria_geral->orderby('categoria_geral', 'ASC')->get();
        // Carrega apenas as subcategorias da categoria geral actual do produto
        $categoria       = Categoria::where('categoria_geral_id', $produto->categoria_geral_id)->get();
        $lote            = lote::all();
        $fornecedor      = Fornecedor::all();

        return view('sistema.produtedite', [
            'Products' => $produto,
            'Ct'       => $categoria,
            'Cageral'  => $categoria_geral,
            'Fr'       => $fornecedor,
            'Lote'     => $lote,
        ]);
    }

    /**
     * Actualiza produto.
     */
    public function produtoupdate(Request $request, $id)
    {
        $produto = Produto::findOrFail($id);
        $produto->fill($request->except('departamento_id'))->save();
        return redirect()->back()->with('success', 'Produto actualizado com sucesso.');
    }

    /**
     * Produtos com alertas de validade.
     */
    public function alert()
    {
        $produto = DB::table('produto')
            ->join('categoria', 'produto.categoria_id', '=', 'categoria.id')
            ->leftJoin('lote', 'lote.produto_id', '=', 'produto.id')
            ->select('produto.*', 'categoria.categoria', 'lote.validade as data_expiracao')
            ->get();

        return view('sistema.alert', ['Products' => $produto]);
    }

    /**
     * Cria novo produto (catálogo global).
     */
    public function create(Request $request)
    {
        $categoria_geral = categoria_geral::all();
        $categoria       = Categoria::all();
        $fornecedor      = Fornecedor::all();

        // Verifica duplicado pelo nome (global, sem departamento)
        if (DB::table('produto')->where('produto', $request['produto'])->count() == 0) {
            Produto::create([
                'produto'          => $request['produto'],
                'apresentacao'     => $request['apresentacao'],
                'codigo'           => $request['codigo'],
                'categoria_id'     => $request['categoria_id'],
                'categoria_geral_id' => $request['setor_id'],
                'data_aquisicao'   => $request['data_aquisicao'],
                'quantidade'       => $request['quantidade'],
                'stokminimo'       => $request['stokminimo'],
            ]);

            return view('sistema.createproduto', [
                'sms'     => 'Sucesso!',
                'Ct'      => $categoria,
                'Fr'      => $fornecedor,
                'Cageral' => $categoria_geral,
            ]);
        } else {
            return view('sistema.createproduto', [
                'sms'     => 'Este produto já existe no catálogo.',
                'Ct'      => $categoria,
                'Fr'      => $fornecedor,
                'Cageral' => $categoria_geral,
            ]);
        }
    }

    /**
     * Elimina produto.
     */
    public function destroy($id)
    {
        if ($redirect = $this->abortSeNaoPodeEliminar()) return $redirect;

        $produto = Produto::find($id);
        if (!$produto) return redirect()->back()->with('error', 'Produto não encontrado.');
        $produto->delete();
        return redirect()->route('produto.verp')->with('success', 'Produto eliminado com sucesso.');
    }

    /**
     * Lista de requisições (utilizador normal).
     */
    public function show()
    {
        $requisicao1 = DB::table('departamento')
            ->join('requisicao', 'departamento.id', '=', 'requisicao.departamento_id')
            ->join('users', 'requisicao.users_id', '=', 'users.id')
            ->select('departamento.departamento', 'requisicao.*', 'users.name')
            ->paginate(5);

        return view('sistema.lerrequisicao', ['Requi' => $requisicao1]);
    }

    /**
     * Marca requisição como atendida.
     */
    public function updaterequi($id)
    {
        $req = requisicao::find($id);
        if (!$req) return redirect()->back();
        $req->statos = 'atendido';
        $req->update();
        return redirect()->route('verrequisicao.showr');
    }

    public function requisicao()
    {
        return view('sistema.requisicao');
    }

    public function crearrequisicao(Request $request)
    {
        requisicao::create([
            'requisicao'      => $request['requisicao'],
            'data'            => date('d-m-y'),
            'departamento_id' => $request['departamento_id'],
            'users_id'        => $request['user_id'],
            'statos'          => 'Pendente',
        ]);

        return view('sistema.requisicao', ['sms' => 'Requisição enviada com sucesso!']);
    }
}
