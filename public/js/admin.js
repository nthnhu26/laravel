// Toggle sidebar on mobile
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.sidebar');
    const toggleBtn = document.createElement('button');
    toggleBtn.className = 'btn btn-primary d-md-none position-fixed top-0 start-0 m-2';
    toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
    document.body.appendChild(toggleBtn);

    toggleBtn.addEventListener('click', function() {
        sidebar.classList.toggle('open');
    });
});