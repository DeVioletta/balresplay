document.addEventListener('DOMContentLoaded', () => {
    const hamburger = document.getElementById('hamburger');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    if (hamburger) hamburger.addEventListener('click', () => sidebar.classList.add('show'));
    if (overlay) overlay.addEventListener('click', () => sidebar.classList.remove('show'));

    const modal = document.getElementById('account-modal');
    const addBtn = document.getElementById('add-account-btn');
    const closeBtn = document.getElementById('modal-close');
    const cancelBtn = document.getElementById('modal-cancel-btn');
    const form = document.getElementById('account-form');
    const modalTitle = document.getElementById('modal-title');
    const passwordInput = document.getElementById('password');
    const passwordHint = document.getElementById('password-hint');
    const statusToggle = document.getElementById('status-toggle-group');
    const statusCheckbox = document.getElementById('status');
    const userIdInput = document.getElementById('user_id');
    const usernameInput = document.getElementById('username');
    const roleInput = document.getElementById('role');
    
    const closeModal = () => {
        modal.classList.remove('show');
        form.reset();
    };

    addBtn.addEventListener('click', () => {
        form.reset();
        userIdInput.value = '';
        modalTitle.textContent = 'Tambah Akun Baru';
        passwordInput.required = true;
        passwordHint.textContent = 'Wajib diisi untuk akun baru.';
        statusToggle.style.display = 'none';
        modal.classList.add('show');
    });
    
    document.querySelectorAll('.btn-edit-account').forEach(button => {
        button.addEventListener('click', (e) => {
            const btn = e.currentTarget;
            userIdInput.value = btn.dataset.id;
            usernameInput.value = btn.dataset.username;
            roleInput.value = btn.dataset.role;
            statusCheckbox.checked = (btn.dataset.status == 1);
            modalTitle.textContent = 'Edit Akun: ' + btn.dataset.username;
            passwordInput.required = false;
            passwordHint.textContent = 'Kosongkan jika tidak ingin ganti password.';
            statusToggle.style.display = 'flex';
            modal.classList.add('show');
        });
    });

    if (closeBtn) closeBtn.addEventListener('click', closeModal);
    if (cancelBtn) cancelBtn.addEventListener('click', closeModal);
    if (modal) {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) closeModal();
        });
    }

    const togglePassword = document.getElementById('toggle-password');
    if (togglePassword) {
        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    }
});