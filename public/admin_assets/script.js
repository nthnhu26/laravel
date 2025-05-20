document.addEventListener('DOMContentLoaded', () => {
    // Toggle Sidebar
    const toggleSidebar = document.getElementById('toggleSidebar');
    const sidebar = document.getElementById('sidebar');
    const navbar = document.getElementById('navbar');
    const mainWrapper = document.getElementById('mainWrapper');

    if (toggleSidebar) {
        toggleSidebar.addEventListener('click', () => {
            sidebar.classList.toggle('collapsed');
            navbar.classList.toggle('collapsed');
            mainWrapper.classList.toggle('collapsed');
        });
    } else {
        console.error('Toggle sidebar button not found!');
    }

    
});