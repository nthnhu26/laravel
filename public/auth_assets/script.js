
    // Toggle visibility for Password
    document.getElementById('togglePassword').addEventListener('click', function () {
        const passwordField = document.getElementById('password');
        const icon = document.getElementById('toggleIconPassword');
        const type = passwordField.getAttribute('type');
        passwordField.setAttribute('type', type === 'password' ? 'text' : 'password');
        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
    });


    // Toggle visibility for Password Confirmation
    document.getElementById('togglePasswordConfirmation').addEventListener('click', function () {
        const passwordConfirmField = document.getElementById('password_confirmation');
        const icon = document.getElementById('toggleIconConfirmation');
        const type = passwordConfirmField.getAttribute('type');
        passwordConfirmField.setAttribute('type', type === 'password' ? 'text' : 'password');
        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
    });
