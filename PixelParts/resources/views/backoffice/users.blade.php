@extends('layouts.backoffice')

@section('title', 'Gestão de Utilizadores - Backoffice')
@section('page-title', 'Gestão de Utilizadores')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between">
        <div class="flex flex-col sm:flex-row gap-4">
            <input type="text" id="search-users" placeholder="Pesquisar utilizadores..."
                class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-64">
            <select id="role-filter"
                class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">Todos os Tipos</option>
                <option value="admin">Administrador</option>
                <option value="customer">Utilizador</option>
            </select>
        </div>
        <button id="add-user-btn"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Adicionar Utilizador
        </button>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilizador
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data de
                        Registo</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody id="users-table" class="bg-white divide-y divide-gray-200">
                @foreach ($users as $user)
                    <tr class="hover:bg-gray-50">
                        <!-- Nome -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $user->name }}
                        </td>

                        <!-- Email -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $user->email }}
                        </td>
                        <!-- Tipo -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if ($user->role === 'admin')
                                <span
                                    class="px-3 py-1 bg-purple-100 text-purple-800 text-xs font-medium rounded-full">Administrador</span>
                            @else
                                <span
                                    class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">Utilizador</span>
                            @endif
                        </td>

                        <!-- Data de Registo -->
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}
                        </td>


                        <!-- Ações -->
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <button class="edit-btn text-blue-600 hover:text-blue-900 mr-2" data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}" data-email="{{ $user->email }}">
                                Editar
                            </button>
                            <button class="delete-btn text-red-600 hover:text-red-900" data-id="{{ $user->id }}">
                                Excluir
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Add/Edit User Modal -->
    <div id="user-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4">
            <form id="user-form" class="p-6 space-y-4">
                @csrf
                <input type="hidden" id="user-id">
                <div class="p-6 border-b border-gray-200">
                    <h3 id="user-modal-title" class="text-xl font-semibold text-gray-800">Adicionar Utilizador</h3>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                    <input type="text" id="user-name" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" id="user-email" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                    <select id="user-role" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Selecionar...</option>
                        <option value="admin">Administrador</option>
                        <option value="customer">Cliente</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input type="password" id="user-password"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <p class="text-xs text-gray-500 mt-1">Deixar vazio para manter a password atual (apenas na edição)</p>
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" id="cancel-user-btn"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">Cancelar</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Guardar</button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ asset('js/backofficeUsers.js') }}"></script>

@endsection
