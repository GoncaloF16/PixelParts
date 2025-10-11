document.addEventListener('DOMContentLoaded', () => {
    const modal = document.getElementById('user-modal');
    const modalTitle = document.getElementById('user-modal-title');
    const userForm = document.getElementById('user-form');
    const inputId = document.getElementById('user-id');
    const inputName = document.getElementById('user-name');
    const inputEmail = document.getElementById('user-email');
    const inputRole = document.getElementById('user-role');
    const inputPassword = document.getElementById('user-password');
    const cancelBtn = document.getElementById('cancel-user-btn');
    const tbody = document.getElementById('users-table');

    // Abrir modal para adicionar
    document.getElementById('add-user-btn').addEventListener('click', () => {
        modalTitle.textContent = 'Adicionar Utilizador';
        inputId.value = '';
        inputName.value = '';
        inputEmail.value = '';
        inputRole.value = '';
        inputPassword.value = '';
        modal.classList.remove('hidden');
    });

    // Abrir modal para editar
    tbody.addEventListener('click', (e) => {
        if (e.target.classList.contains('edit-btn')) {
            const btn = e.target;
            modalTitle.textContent = 'Editar Utilizador';
            inputId.value = btn.dataset.id;
            inputName.value = btn.dataset.name;
            inputEmail.value = btn.dataset.email;
            inputRole.value = btn.dataset.role || 'customer';
            inputPassword.value = '';
            modal.classList.remove('hidden');
        }
    });

    // Fechar modal
    cancelBtn.addEventListener('click', () => modal.classList.add('hidden'));

    // Submit do formulário
    userForm.addEventListener('submit', e => {
        e.preventDefault();

        const id = inputId.value;
        const url = id ? `/backoffice/users/${id}` : '/backoffice/users';
        const method = id ? 'PUT' : 'POST';

        fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('#user-form input[name="_token"]').value
            },
            body: JSON.stringify({
                name: inputName.value,
                email: inputEmail.value,
                role: inputRole.value,
                password: inputPassword.value
            })
        })
        .then(res => res.json())
        .then(data => {
            alert(data.message);

            if (id) {
                // EDITAR: atualizar a linha existente
                const row = tbody.querySelector(`tr[data-id='${id}']`);
                if (row) {
                    row.querySelector('td:nth-child(1)').textContent = data.user.name;
                    row.querySelector('td:nth-child(2)').textContent = data.user.email;
                    const span = row.querySelector('td:nth-child(3) span');
                    span.textContent = data.user.role === 'admin' ? 'Administrador' : 'Utilizador';
                    span.className = data.user.role === 'admin'
                        ? 'px-3 py-1 bg-purple-100 text-purple-800 text-xs font-medium rounded-full'
                        : 'px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full';
                }
            } else {
                // ADICIONAR: inserir nova linha
                const newRow = document.createElement('tr');
                newRow.classList.add('hover:bg-gray-50');
                newRow.dataset.id = data.user.id;
                newRow.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${data.user.name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${data.user.email}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <span class="px-3 py-1 ${data.user.role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'} text-xs font-medium rounded-full">
                            ${data.user.role === 'admin' ? 'Administrador' : 'Utilizador'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${new Date(data.user.created_at).toLocaleDateString('pt-PT')}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button class="edit-btn text-blue-600 hover:text-blue-900 mr-2" data-id="${data.user.id}" data-name="${data.user.name}" data-email="${data.user.email}" data-role="${data.user.role}">Editar</button>
                        <button class="delete-btn text-red-600 hover:text-red-900" data-id="${data.user.id}">Excluir</button>
                    </td>
                `;
                tbody.prepend(newRow);
            }

            // Fechar modal e limpar form
            modal.classList.add('hidden');
            inputId.value = '';
            inputName.value = '';
            inputEmail.value = '';
            inputRole.value = '';
            inputPassword.value = '';
        })
        .catch(err => console.error(err));
    });

    // Excluir usuário
    tbody.addEventListener('click', (e) => {
        if (e.target.classList.contains('delete-btn')) {
            const id = e.target.dataset.id;
            if (confirm('Tem a certeza que deseja excluir este utilizador?')) {
                fetch(`/backoffice/users/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('#user-form input[name="_token"]').value
                    }
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    const row = tbody.querySelector(`tr[data-id='${id}']`);
                    if (row) row.remove();
                });
            }
        }
    });
});
