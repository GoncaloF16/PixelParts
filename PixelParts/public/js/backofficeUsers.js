// backofficeUsers.js
document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("user-modal");
    const modalTitle = document.getElementById("user-modal-title");
    const userForm = document.getElementById("user-form");
    const inputId = document.getElementById("user-id");
    const inputName = document.getElementById("user-name");
    const inputEmail = document.getElementById("user-email");
    const inputRole = document.getElementById("user-role");
    const inputPassword = document.getElementById("user-password");
    const cancelBtn = document.getElementById("cancel-user-btn");
    const tbody = document.getElementById("users-table");
    const inputMode = document.getElementById("user-mode");
    const saveBtn = document.getElementById("save-user-btn");
    const searchInput = document.getElementById("search-users");
    const roleFilter = document.getElementById("role-filter");

    // Confirmation modals
    const saveConfirmModal = document.getElementById("save-confirm-modal");
    const saveConfirmTitle = document.getElementById("save-confirm-title");
    const saveConfirmMessage = document.getElementById("save-confirm-message");
    const confirmSaveBtn = document.getElementById("confirm-save");
    const cancelSaveConfirmBtn = document.getElementById("cancel-save-confirm");

    const deleteConfirmModal = document.getElementById("delete-confirm-modal");
    const deleteConfirmMessage = document.getElementById("delete-confirm-message");
    const confirmDeleteBtn = document.getElementById("confirm-delete");
    const cancelDeleteConfirmBtn = document.getElementById("cancel-delete-confirm");

    let pendingDeleteId = null;

    /* ------------------ FILTROS ------------------ */
    function applyFilters() {
        const search = searchInput.value;
        const role = roleFilter.value;

        const url = new URL(window.location.href);
        url.searchParams.set('search', search);
        url.searchParams.set('role', role);

        if (!search) url.searchParams.delete('search');
        if (!role) url.searchParams.delete('role');

        window.location.href = url.toString();
    }

    // Search on typing (debounce)
    let searchTimeout;
    searchInput.addEventListener('input', () => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(applyFilters, 500);
    });

    // Role filter
    roleFilter.addEventListener('change', applyFilters);

    // Load filter values from URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('search')) {
        searchInput.value = urlParams.get('search');
    }
    if (urlParams.has('role')) {
        roleFilter.value = urlParams.get('role');
    }

    /* ------------------ HELPERS ------------------ */
    function setFormMode(mode) {
        inputMode.value = mode;

        const isView = mode === 'view';

        // Disable/enable inputs
        inputName.readOnly = isView;
        inputEmail.readOnly = isView;
        inputRole.disabled = isView;
        inputPassword.readOnly = isView;

        // Add/remove visual feedback
        [inputName, inputEmail, inputRole, inputPassword].forEach(input => {
            if (isView) {
                input.classList.add('bg-gray-100', 'cursor-not-allowed');
            } else {
                input.classList.remove('bg-gray-100', 'cursor-not-allowed');
            }
        });

        // Show/hide submit button
        if (isView) {
            saveBtn.classList.add('hidden');
        } else {
            saveBtn.classList.remove('hidden');
        }
    }

    /* ------------------ HELPERS ------------------ */
    function measureHiddenElement(el) {
        const originalDisplay = el.style.display;
        const originalVisibility = el.style.visibility;
        const originalPosition = el.style.position;

        el.style.display = "block";
        el.style.visibility = "hidden";
        el.style.position = "absolute";
        el.style.left = "-9999px";
        el.style.top = "-9999px";

        const rect = el.getBoundingClientRect();

        el.style.display = originalDisplay;
        el.style.visibility = originalVisibility;
        el.style.position = originalPosition;
        el.style.left = "";
        el.style.top = "";

        return rect;
    }

    function clampHorizontal(left, menuWidth) {
        const padding = 8;
        const minLeft = padding;
        const maxLeft = window.innerWidth - menuWidth - padding;
        return Math.min(Math.max(left, minLeft), Math.max(minLeft, maxLeft));
    }

    /* ------------------ MODAL (ADD / EDIT) ------------------ */
    document.getElementById("add-user-btn").addEventListener("click", () => {
        setFormMode('add');
        modalTitle.textContent = "Adicionar Utilizador";
        inputId.value = "";
        inputName.value = "";
        inputEmail.value = "";
        inputRole.value = "";
        inputPassword.value = "";
        modal.classList.remove("hidden");
    });

    tbody.addEventListener("click", (e) => {
        const viewBtn = e.target.closest(".view-btn");
        if (viewBtn) {
            e.stopPropagation(); // Prevent the click from closing the modal

            // Close the dropdown manually
            const dropdown = viewBtn.closest('[data-dropdown-menu]');
            if (dropdown) {
                dropdown.classList.add('hidden');
                if (openDropdown) {
                    openDropdown.menu.removeAttribute("style");
                    openDropdown = null;
                }
            }

            setFormMode('view');
            modalTitle.textContent = "Visualizar Utilizador";
            inputId.value = viewBtn.dataset.id || "";
            inputName.value = viewBtn.dataset.name || "";
            inputEmail.value = viewBtn.dataset.email || "";
            inputRole.value = viewBtn.dataset.role || "user";
            inputPassword.value = "********"; // Placeholder for view mode
            modal.classList.remove("hidden");
            return;
        }

        const editBtn = e.target.closest(".edit-btn");
        if (editBtn) {
            e.stopPropagation(); // Prevent the click from closing the modal

            // Close the dropdown manually
            const dropdown = editBtn.closest('[data-dropdown-menu]');
            if (dropdown) {
                dropdown.classList.add('hidden');
                if (openDropdown) {
                    openDropdown.menu.removeAttribute("style");
                    openDropdown = null;
                }
            }

            setFormMode('edit');
            modalTitle.textContent = "Editar Utilizador";
            inputId.value = editBtn.dataset.id || "";
            inputName.value = editBtn.dataset.name || "";
            inputEmail.value = editBtn.dataset.email || "";
            inputRole.value = editBtn.dataset.role || "user";
            inputPassword.value = "";
            modal.classList.remove("hidden");
        }
    });

    cancelBtn.addEventListener("click", () => modal.classList.add("hidden"));

    /* ------------------ SAVE CONFIRMATION ------------------ */
    saveBtn.addEventListener("click", (e) => {
        e.preventDefault();
        const id = inputId.value;

        if (id) {
            saveConfirmTitle.textContent = "Confirmar edição";
            saveConfirmMessage.textContent = "Pretende guardar as alterações ao utilizador?";
        } else {
            saveConfirmTitle.textContent = "Confirmar adição";
            saveConfirmMessage.textContent = "Pretende adicionar este novo utilizador?";
        }

        saveConfirmModal.classList.remove('hidden');
        saveConfirmModal.classList.add('flex');
    });

    confirmSaveBtn.addEventListener("click", () => {
        saveConfirmModal.classList.add('hidden');
        saveConfirmModal.classList.remove('flex');
        submitForm();
    });

    cancelSaveConfirmBtn.addEventListener("click", () => {
        saveConfirmModal.classList.add('hidden');
        saveConfirmModal.classList.remove('flex');
    });

    /* ------------------ SUBMIT (CREATE / UPDATE) ------------------ */
    function submitForm() {

        const id = inputId.value;
        const url = id ? `/backoffice/users/${id}` : "/backoffice/users";
        const method = id ? "PUT" : "POST";

        fetch(url, {
            method,
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    '#user-form input[name="_token"]'
                ).value,
            },
            body: JSON.stringify({
                name: inputName.value,
                email: inputEmail.value,
                role: inputRole.value,
                password: inputPassword.value,
            }),
        })
            .then((res) => res.json())
            .then((data) => {
                if (id) {
                    // update existing row
                    const row = tbody.querySelector(`tr[data-id='${id}']`);
                    if (row) {
                        row.querySelector("td:nth-child(1)").textContent =
                            data.user.name;
                        row.querySelector("td:nth-child(2)").textContent =
                            data.user.email;
                        const span = row.querySelector("td:nth-child(3) span");
                        span.textContent =
                            data.user.role === "admin"
                                ? "Administrador"
                                : "Utilizador";
                        span.className =
                            data.user.role === "admin"
                                ? "px-3 py-1 bg-purple-100 text-purple-800 text-xs font-medium rounded-full"
                                : "px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full";
                    }
                } else {
                    // create new row
                    // dentro do userForm submit, no else (novo row)
                    const newRow = document.createElement("tr");
                    newRow.classList.add("hover:bg-gray-50");
                    newRow.dataset.id = data.user.id;
                    newRow.innerHTML = `
  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${escapeHtml(
      data.user.name
  )}</td>
  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${escapeHtml(
      data.user.email
  )}</td>
  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
    <span class="px-3 py-1 ${
        data.user.role === "admin"
            ? "bg-purple-100 text-purple-800"
            : "bg-blue-100 text-blue-800"
    } text-xs font-medium rounded-full">
      ${data.user.role === "admin" ? "Administrador" : "Utilizador"}
    </span>
  </td>
  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${new Date(
      data.user.created_at
  ).toLocaleDateString("pt-PT")}</td>
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
          <button class="edit-btn w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center gap-2"
            data-id="${data.user.id}" data-name="${escapeHtmlAttr(
                        data.user.name
                    )}" data-email="${escapeHtmlAttr(
                        data.user.email
                    )}" data-role="${data.user.role}">
            Editar
          </button>
          <button class="delete-btn w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 flex items-center gap-2"
            data-id="${data.user.id}">
            Excluir
          </button>
        </div>
      </div>
    </div>
  </td>
`;
                    tbody.prepend(newRow);
                }

                modal.classList.add("hidden");
            })
            .catch((err) => console.error(err));
    }

    /* ------------------ DELETE CONFIRMATION ------------------ */
    tbody.addEventListener("click", (e) => {
        const del = e.target.closest(".delete-btn");
        if (!del) return;
        const id = del.dataset.id;
        if (!id) return;

        pendingDeleteId = id;
        const userName = del.closest('tr').querySelector('td:first-child').textContent.trim();
        deleteConfirmMessage.textContent = `Tem a certeza que pretende apagar o utilizador "${userName}"?`;
        deleteConfirmModal.classList.remove('hidden');
        deleteConfirmModal.classList.add('flex');
    });

    confirmDeleteBtn.addEventListener("click", () => {
        if (!pendingDeleteId) return;

        fetch(`/backoffice/users/${pendingDeleteId}`, {
            method: "DELETE",
            headers: {
                "X-CSRF-TOKEN": document.querySelector(
                    '#user-form input[name="_token"]'
                ).value,
            },
        })
            .then((res) => res.json())
            .then(() => {
                const row = tbody.querySelector(`tr[data-id='${pendingDeleteId}']`);
                if (row) row.remove();
                deleteConfirmModal.classList.add('hidden');
                deleteConfirmModal.classList.remove('flex');
                pendingDeleteId = null;
            })
            .catch((err) => console.error(err));
    });

    cancelDeleteConfirmBtn.addEventListener("click", () => {
        deleteConfirmModal.classList.add('hidden');
        deleteConfirmModal.classList.remove('flex');
        pendingDeleteId = null;
    });

    let openDropdown = null;

    document.addEventListener("click", (e) => {
        const trigger = e.target.closest("[data-dropdown-trigger]");

        if (!trigger) {
            document
                .querySelectorAll("[data-dropdown-menu]")
                .forEach((m) => m.classList.add("hidden"));
            if (openDropdown) {
                openDropdown.menu.removeAttribute("style");
                openDropdown = null;
            }
            return;
        }

        document
            .querySelectorAll("[data-dropdown-menu]")
            .forEach((m) => {
                if (m !== trigger.parentElement.querySelector("[data-dropdown-menu]")) {
                    m.classList.add("hidden");
                }
            });

        e.stopPropagation();
        const menu = trigger.parentElement.querySelector(
            "[data-dropdown-menu]"
        );
        if (!menu) return;

        const wasHidden = menu.classList.contains("hidden");
        if (wasHidden) {
            menu.classList.remove("hidden");
            const measured = measureHiddenElement(menu);
            menu.classList.add("hidden");

            const rect = trigger.getBoundingClientRect();
            const menuWidth = measured.width;
            const menuHeight = measured.height;

            menu.style.position = "fixed";
            let top = rect.bottom + 4;
            if (rect.bottom + menuHeight > window.innerHeight)
                top = rect.top - menuHeight - 4;
            let left = rect.right - menuWidth;
            left = clampHorizontal(left, menuWidth);

            menu.style.top = `${Math.round(top)}px`;
            menu.style.left = `${Math.round(left)}px`;
            menu.style.zIndex = "99999";

            menu.classList.remove("hidden");
            openDropdown = { trigger, menu };
        } else {
            menu.classList.toggle("hidden");
            if (!menu.classList.contains("hidden")) {
                openDropdown = { trigger, menu };
            } else {
                openDropdown = null;
                menu.removeAttribute("style");
            }
        }
    });

    window.addEventListener("click", (e) => {
        // Don't close dropdowns if click is inside a modal
        if (e.target.closest('#user-modal') ||
            e.target.closest('#save-confirm-modal') ||
            e.target.closest('#delete-confirm-modal')) {
            return;
        }

        if (openDropdown) {
            openDropdown.menu.classList.add("hidden");
            openDropdown.menu.removeAttribute("style");
            openDropdown = null;
        }
    });

    /* ------------------ UTIL ------------------ */
    function escapeHtml(str) {
        if (!str) return "";
        return String(str)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
    function escapeHtmlAttr(str) {
        return escapeHtml(str).replace(/"/g, "&quot;");
    }
});
