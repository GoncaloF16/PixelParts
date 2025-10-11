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
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            modalTitle.textContent = 'Editar Utilizador';
            inputId.value = btn.dataset.id;
            inputName.value = btn.dataset.name;
            inputEmail.value = btn.dataset.email;
            inputRole.value = btn.dataset.role ?? 'customer'; // se precisar
            inputPassword.value = '';
            modal.classList.remove('hidden');
        });
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
                'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
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
                // Atualiza linha existente
                const row = document.querySelector(`#users-table tr td:first-child:contains('${data.user.name}')`);
                location.reload(); // pode substituir por update dinâmico
            } else {
                location.reload();
            }

            modal.classList.add('hidden');
        })
        .catch(err => console.error(err));
    });

    // Excluir utilizador
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            if (confirm('Tem certeza que deseja excluir este utilizador?')) {
                fetch(`/backoffice/users/${btn.dataset.id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
                    }
                })
                .then(res => res.json())
                .then(data => {
                    alert(data.message);
                    const row = document.querySelector(`#users-table tr[data-id='${btn.dataset.id}']`);
                    if(row) row.remove();
                });
            }
        });
    });
});
