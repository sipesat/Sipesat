const registerLink = document.getElementById('register-link');
const loginLink = document.getElementById('login-link');
const loginBox = document.getElementById('login-box');
const registerBox = document.getElementById('register-box');

registerLink.addEventListener('click', (e) => {
    e.preventDefault();
    toggleFormVisibility(registerBox, loginBox);
});

loginLink.addEventListener('click', (e) => {
    e.preventDefault();
    toggleFormVisibility(loginBox, registerBox);
});

function toggleFormVisibility(showBox, hideBox) {
    hideBox.style.opacity = 0;
    hideBox.style.transform = "translateY(20px)";
    setTimeout(() => {
        hideBox.style.display = 'none';
        showBox.style.display = 'block';
        setTimeout(() => {
            showBox.style.opacity = 1;
            showBox.style.transform = "translateY(0)";
        }, 10);
    }, 300);
}


