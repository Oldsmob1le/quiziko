document.addEventListener('DOMContentLoaded', function () {
    const switchToRegisterFromLogin = document.getElementById('switchToRegisterFromLogin');
    const switchToLoginFromRegister = document.getElementById('switchToLoginFromRegister');

    const loginModal = new bootstrap.Modal(document.getElementById('loginModal'));
    const registerModal = new bootstrap.Modal(document.getElementById('registerModal'));

    switchToRegisterFromLogin.addEventListener('click', function (e) {
        e.preventDefault();
        loginModal.hide();  
        registerModal.show();
    });

    switchToLoginFromRegister.addEventListener('click', function (e) {
        e.preventDefault();
        registerModal.hide(); 
        loginModal.show(); 
    });

    $('#loginModal').on('hidden.bs.modal', function () {
        registerModal.hide();
    });

    $('#registerModal').on('hidden.bs.modal', function () {
        loginModal.hide();
    });
});