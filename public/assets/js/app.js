// public/js/app.js
document.addEventListener('DOMContentLoaded', function () {
    const themeToggle = document.getElementById('themeToggle');
    const body = document.body;

    if (localStorage.getItem('theme') === 'dark') {
        body.classList.add('dark-mode');
        themeToggle.querySelector('.toggle-thumb').innerHTML = '<i class="bi bi-moon-fill"></i>';
    }

    themeToggle.addEventListener('click', function () {
        body.classList.toggle('dark-mode');
        const isDark = body.classList.contains('dark-mode');
        localStorage.setItem('theme', isDark ? 'dark' : 'light');
        themeToggle.querySelector('.toggle-thumb').innerHTML = isDark 
            ? '<i class="bi bi-moon-fill"></i>' 
            : '<i class="bi bi-sun-fill"></i>';
    });
});