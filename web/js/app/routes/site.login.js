$(function() {
    let restorePassBox = $('.js-restore-pass-box'),
        loginFormBox = $('.js-login-form-box');

    restorePassBox.find('.js-back-to-login-btn').on('click', function() {
        restorePassBox.hide();
        loginFormBox.show();

        return false;
    });

    loginFormBox.find('.js-forgot-pass-btn').on('click', function() {
        loginFormBox.hide();
        restorePassBox.show();

        return false;
    });
});