@extends('layouts.backoffice')

@section('title', 'Gestão de Utilizadores - Backoffice')
@section('page-title', 'Gestão de Utilizadores')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row gap-4 justify-between">
        <div class="flex flex-col sm:flex-row gap-4">
            <input type="text" id="search-users" placeholder="Pesquisar utilizadores..."
                class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 w-full sm:w-64">
            <div class="relative">
                <select id="role-filter"
                    class="appearance-none px-4 py-2 pr-10 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white cursor-pointer hover:border-gray-400 transition min-w-[180px]">
                    <option value="">Todos os Tipos</option>
                    <option value="admin">Administrador</option>
                    <option value="user">Utilizador</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>
        </div>
        <button id="add-user-btn"
            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Adicionar Utilizador
        </button>
    </div>

    <div class="bg-white rounded-lg shadow overflow-hidden overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilizador
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th class="hidden md:table-cell px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data de
                        Registo</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody id="users-table" class="bg-white divide-y divide-gray-200">
                @forelse ($users as $user)
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
                        <td class="hidden md:table-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}
                        </td>


                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium relative overflow-visible">
                            <div class="relative inline-block text-left">
                                <button type="button"
                                    class="inline-flex items-center gap-2 rounded-md bg-gray-600 text-white px-4 py-2 text-sm font-medium hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                                    data-dropdown-trigger>
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15.5a3.5 3.5 0 100-7 3.5 3.5 0 000 7z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 01-2.83 2.83l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-4 0v-.09a1.65 1.65 0 00-1-1.51 1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 010-4h.09a1.65 1.65 0 001.51-1 1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 012.83-2.83l.06.06a1.65 1.65 0 001.82.33h.09a1.65 1.65 0 001-1.51V3a2 2 0 014 0v.09a1.65 1.65 0 001 1.51h.09a1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 2.83l-.06.06a1.65 1.65 0 00-.33 1.82v.09a1.65 1.65 0 001.51 1H21a2 2 0 010 4h-.09a1.65 1.65 0 00-1.51 1z" />
                                    </svg>

                                    Ações
                                </button>

                                <div class="absolute right-0 mt-2 w-40 origin-top-right rounded-md bg-white shadow-xl ring-1 ring-black ring-opacity-5 hidden"
                                    data-dropdown-menu>
                                    <div class="py-1">
                                        <button
                                            class="view-btn w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2"
                                            data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                            data-email="{{ $user->email }}" data-role="{{ $user->role }}">
                                            <i data-lucide="eye" class="w-4 h-4 text-blue-500"></i>
                                            Visualizar
                                        </button>
                                        <button
                                            class="edit-btn w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2"
                                            data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                            data-email="{{ $user->email }}" data-role="{{ $user->role }}">
                                            <i data-lucide="edit" class="w-4 h-4 text-gray-500"></i>
                                            Editar
                                        </button>
                                        <button
                                            class="delete-btn w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 flex items-center gap-2"
                                            data-id="{{ $user->id }}">
                                            <i data-lucide="trash-2" class="w-4 h-4 text-red-500"></i>
                                            Excluir
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <div class="flex flex-col items-center gap-2">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p class="text-lg font-medium">Nenhum utilizador encontrado</p>
                                <p class="text-sm">Tente ajustar os filtros de pesquisa</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Paginação -->
    <div class="mt-4">
        {{ $users->links() }}
    </div>

    <!-- Add/Edit User Modal -->
    <div id="user-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-lg mx-4">
            <form id="user-form" class="p-6 space-y-4">
                @csrf
                <input type="hidden" id="user-id">
                <input type="hidden" id="user-mode" value="add">
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
                        <option value="user">Utilizador</option>
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
