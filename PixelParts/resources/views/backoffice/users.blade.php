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

    <form method="POST" action="{{ route('backoffice.users.bulk-delete') }}">
        @csrf

    <div class="bg-white rounded-lg shadow overflow-hidden overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-12">
                        <input id="select-all" type="checkbox" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilizador
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                </tr>
            </thead>
            <tbody id="users-table" class="bg-white divide-y divide-gray-200">
                @forelse ($users as $user)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="checkbox" name="selected[]" value="{{ $user->id }}" class="row-checkbox h-4 w-4 text-blue-600 border-gray-300 rounded">
                        </td>
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
                                        <button type="button"
                                            class="view-btn w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2"
                                            data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                            data-email="{{ $user->email }}" data-role="{{ $user->role }}"
                                            data-created="{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') }}">
                                            <i data-lucide="eye" class="w-4 h-4 text-blue-500"></i>
                                            Visualizar
                                        </button>
                                        <button type="button"
                                            class="edit-btn w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2"
                                            data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                                            data-email="{{ $user->email }}" data-role="{{ $user->role }}"
                                            data-created="{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') }}">
                                            <i data-lucide="edit" class="w-4 h-4 text-gray-500"></i>
                                            Editar
                                        </button>
                                        <button type="button"
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

    <!-- Bulk actions + Pagination -->
    <div class="mt-6 flex items-center justify-between gap-3 flex-col sm:flex-row">
        <button id="bulk-delete-btn" type="button"
            class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
            disabled>
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0V5a2 2 0 012-2h2a2 2 0 012 2v2" />
            </svg>
            Apagar Seleção
        </button>

        @if($users->hasPages())
            <div class="self-stretch sm:self-auto">
                {{ $users->links('vendor.pagination.backoffice') }}
            </div>
        @endif
    </div>
    </form>

    <!-- Bulk removal confirmation modal -->
    <div id="bulk-delete-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Apagar utilizadores selecionados?</h3>
                        <p class="text-sm text-gray-500 mt-1">Esta ação não pode ser desfeita.</p>
                    </div>
                </div>
                <p id="bulk-delete-count" class="text-sm text-gray-600 mb-6"></p>
                <div class="flex gap-3 justify-end">
                    <button type="button" id="cancel-bulk-delete"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Cancelar
                    </button>
                    <button type="button" id="confirm-bulk-delete"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Sim, apagar
                    </button>
                </div>
            </div>
        </div>
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
                <div id="user-created-container" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Data de Registo</label>
                    <input type="text" id="user-created" readonly
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed">
                </div>
                <div class="flex justify-end gap-2 pt-4">
                    <button type="button" id="cancel-user-btn"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">Cancelar</button>
                    <button type="button" id="save-user-btn"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">Guardar</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Save confirmation modal -->
    <div id="save-confirm-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900" id="save-confirm-title">Confirmar ação</h3>
                        <p class="text-sm text-gray-500 mt-1" id="save-confirm-message">Pretende guardar as alterações?</p>
                    </div>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" id="cancel-save-confirm"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Cancelar
                    </button>
                    <button type="button" id="confirm-save"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Sim, guardar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Removal confirmation modal -->
    <div id="delete-confirm-modal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl w-full max-w-md mx-4">
            <div class="p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Apagar utilizador?</h3>
                        <p class="text-sm text-gray-500 mt-1">Esta ação não pode ser desfeita.</p>
                    </div>
                </div>
                <p id="delete-confirm-message" class="text-sm text-gray-600 mb-6"></p>
                <div class="flex gap-3 justify-end">
                    <button type="button" id="cancel-delete-confirm"
                        class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition">
                        Cancelar
                    </button>
                    <button type="button" id="confirm-delete"
                        class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Sim, apagar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
    // Bulk delete functionality
    (function() {
        const selectAll = document.getElementById('select-all');
        const rowCheckboxes = () => Array.from(document.querySelectorAll('.row-checkbox'));
        const bulkBtn = document.getElementById('bulk-delete-btn');
        const bulkModal = document.getElementById('bulk-delete-modal');
        const bulkForm = bulkBtn ? bulkBtn.closest('form') : null;
        const confirmBtn = document.getElementById('confirm-bulk-delete');
        const cancelBtn = document.getElementById('cancel-bulk-delete');
        const countText = document.getElementById('bulk-delete-count');

        function updateBulkState() {
            const anyChecked = rowCheckboxes().some(cb => cb.checked);
            if (bulkBtn) bulkBtn.disabled = !anyChecked;

            // Atualizar estado do select-all (indeterminate)
            const total = rowCheckboxes().length;
            const checked = rowCheckboxes().filter(cb => cb.checked).length;
            if (checked === 0) {
                if (selectAll) {
                    selectAll.checked = false;
                    selectAll.indeterminate = false;
                }
            } else if (checked === total) {
                if (selectAll) {
                    selectAll.checked = true;
                    selectAll.indeterminate = false;
                }
            } else {
                if (selectAll) {
                    selectAll.checked = false;
                    selectAll.indeterminate = true;
                }
            }
        }

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                rowCheckboxes().forEach(cb => cb.checked = selectAll.checked);
                updateBulkState();
            });
        }

        rowCheckboxes().forEach(cb => cb.addEventListener('change', updateBulkState));

        // Open modal when delete selection is clicked
        if (bulkBtn) {
            bulkBtn.addEventListener('click', function() {
                const checked = rowCheckboxes().filter(cb => cb.checked).length;
                if (checked > 0) {
                    countText.textContent = `Tem a certeza que pretende apagar ${checked} utilizador${checked > 1 ? 'es' : ''}?`;
                    bulkModal.classList.remove('hidden');
                    bulkModal.classList.add('flex');
                }
            });
        }

        // Confirm removal
        if (confirmBtn) {
            confirmBtn.addEventListener('click', function() {
                if (bulkForm) bulkForm.submit();
            });
        }

        // Cancelar modal
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                bulkModal.classList.add('hidden');
                bulkModal.classList.remove('flex');
            });
        }

        // Fechar modal ao clicar fora
        if (bulkModal) {
            bulkModal.addEventListener('click', function(e) {
                if (e.target === bulkModal) {
                    bulkModal.classList.add('hidden');
                    bulkModal.classList.remove('flex');
                }
            });
        }

        // Inicializar estado no load
        updateBulkState();
    })();
    </script>

@endsection
