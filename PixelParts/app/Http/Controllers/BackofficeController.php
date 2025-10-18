<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BackofficeController extends Controller
{
    public function index() {

        $totalUsers = DB::table('users')->count();
        $totalProducts = DB::table('products')->count();

        $products = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.name', 'products.stock', 'categories.name as category_name')
            ->where('products.stock', '<', 10)
            ->orderBy('products.stock', 'asc')
            ->get();
            
        return view('backoffice.dashboard', compact('totalUsers', 'totalProducts', 'products'));

    }

    public function users() {
        $users = DB::table('users')->get();

        return view('backoffice.users', compact('users'));
    }

      public function storeUser(Request $request) {
        $id = DB::table('users')->insertGetId([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt('123456'),
        ]);

        $user = DB::table('users')->where('id', $id)->first();
        return response()->json(['message' => 'Usuário adicionado com sucesso', 'user' => $user]);
    }

    public function updateUser(Request $request, $id) {
        DB::table('users')->where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        $user = DB::table('users')->where('id', $id)->first();
        return response()->json(['message' => 'Usuário atualizado com sucesso', 'user' => $user]);
    }

    public function deleteUser($id) {
        DB::table('users')->where('id', $id)->delete();
        return response()->json(['message' => 'Usuário excluído com sucesso', 'id' => $id]);
    }

    public function stock(Request $request) {

        $search = $request->input('search');
        $category = $request->input('category');

        $query = DB::table('products')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->select('products.*', 'categories.name as category_name');

        if ($search) {
            $query->where('products.name', 'like', '%' . $search . '%');
        }

        if ($category) {
            $query->where('categories.name', $category);
        }

        $products = $query->get();

        return view('backoffice.stock', compact('products'));
    }

    public function exportStockPdf(Request $request)
{
    $query = DB::table('products')
        ->join('categories', 'products.category_id', '=', 'categories.id')
        ->select('products.*', 'categories.name as category_name');

    if ($request->filled('search')) {
        $query->where('products.name', 'like', '%' . $request->search . '%');
    }

    if ($request->filled('category')) {
        $query->where('categories.name', $request->category);
    }

    $products = $query->get();

    $pdf = Pdf::loadView('backoffice.stock-pdf', compact('products'));
    return $pdf->download('stock.pdf');
}
}
