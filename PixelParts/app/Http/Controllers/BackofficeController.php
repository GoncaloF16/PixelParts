<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BackofficeController extends Controller
{
    public function index() {

        $totalUsers = DB::table('users')->count();
        $totalProducts = DB::table('products')->count();

        return view('backoffice.dashboard', compact('totalUsers', 'totalProducts'));

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
}
