const loginForm = document.getElementById('loginForm');
const passwordInput = document.getElementById('password');
const togglePassword = document.getElementById('togglePassword');

togglePassword.addEventListener('click', () => {
    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
    passwordInput.setAttribute('type', type);
    togglePassword.textContent = type === 'password' ? 'Show' : 'Hide';
});

loginForm.addEventListener('submit', (e) => {
    e.preventDefault();
    
    const email = document.getElementById('email').value;
    const password = passwordInput.value;

    console.log("Logging in with:", { email, password });
    
    alert("Login attempt successful! Check the console for data.");
});

const signupForm = document.getElementById('signupForm');
if (signupForm) {
    signupForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const name = document.getElementById('username').value;
        const email = document.getElementById('email').value;
        
        console.log("Registering:", { name, email });
        alert("Account created successfully! Redirecting to login...");
        window.location.href = "login.html"; 
    });
}

const loginForm = document.getElementById('loginForm');
if (loginForm) {
    loginForm.addEventListener('submit', (e) => {
        e.preventDefault();
        alert("Logged in!");
    });
}