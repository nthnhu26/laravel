/* Main Content */
.main-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    background-color: #fff;
    margin-left: 250px;
    padding-top: 60px;
}

.header {
    padding: 15px;
    background-color: #fff;
    border-bottom: 1px solid #ddd;
    display: flex;
    justify-content: space-between;
    align-items: center;
    position: fixed;
    top: 0;
    left: 250px;
    right: 0;
    height: 60px;
    z-index: 1000;
}

.header h2 {
    font-size: 18px;
    color: #333;
}

.content {
    padding: 20px;
    flex: 1;
}

.content-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

/* Language alert */
.language-alert {
    background-color: #d9edf7;
    border: 1px solid #bce8f1;
    color: #31708f;
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
}

/* Form */
.form-container {
    display: flex;
    gap: 20px;
}

.form-main {
    flex: 3;
}

.form-sidebar {
    flex: 1;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 15px;
}

.form-group {
    margin-bottom: 15px;
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
    color: #555;
}

.form-control {
    width: 100%;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

select.form-control {
    height: 36px;
}

.form-check-input {
    margin-top: 0.3rem;
}

/* Language selector */
.language-selector {
    margin-bottom: 20px;
}

.language-selector h4 {
    font-size: 16px;
    margin-bottom: 10px;
    padding-bottom: 5px;
    border-bottom: 1px solid #ddd;
}

.language-item {
    display: flex;
    align-items: center;
    padding: 8px 0;
    cursor: pointer;
}

.language-item.active {
    font-weight: bold;
}

.language-item img {
    width: 20px;
    margin-right: 10px;
}

.language-item .lang-label {
    flex: 1;
}

/* Language content */
.language-content {
    display: none;
}

.language-content.active {
    display: block;
}

/* Image gallery */
.image-gallery {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 10px;
}

.image-gallery-item {
    width: 100px;
    height: 100px;
    overflow: hidden;
    border-radius: 4px;
    border: 1px solid #ddd;
}

.image-gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Form actions */
.form-actions {
    margin-top: 20px;
    display: flex;
    gap: 10px;
}

.btn {
    padding: 8px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
}

.btn-primary {
    background-color: #27ae60;
    color: #fff;
}

.btn-primary:hover {
    background-color: #219955;
}

.btn-secondary {
    background-color: #6c757d;
    color: #fff;
}

.btn-secondary:hover {
    background-color: #5a6268;
}

/* Card */
.card {
    background-color: #fff;
    border-radius: 4px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.card-header {
    border-bottom: 1px solid #ddd;
    padding: 10px 15px;
}

.card-body {
    padding: 15px;
}

/* Status badges */
.status-badge {
    padding: 4px 8px;
    border-radius: 50px;
    font-size: 12px;
    font-weight: 500;
}

.status-active {
    background-color: #d4edda;
    color: #155724;
}

.status-inactive {
    background-color: #f8d7da;
    color: #721c24;
}

/* Sidebar */
.sidebar {
    background-color: #34495e;
    color: #ecf0f1;
    height: 100vh;
    position: fixed;
    width: 250px;
    padding: 0;
    box-shadow: 2px 0 5px rgba(0,0,0,0.1);
    overflow-y: auto;
}

.sidebar-header {
    padding: 15px;
    background-color: rgba(0,0,0,0.1);
    font-size: 1.2rem;
    font-weight: bold;
}

.sidebar .nav-link {
    color: #ecf0f1;
    padding: 12px 15px;
    border-left: 4px solid transparent;
}

.sidebar .nav-link:hover {
    background-color: rgba(255,255,255,0.05);
}

.sidebar .nav-link.active {
    background-color: #2c3e50;
    border-left-color: #3498db;
}

/* Responsive */
@media (max-width: 768px) {
    .main-content {
        margin-left: 0;
    }

    .header {
        left: 0;
    }

    .sidebar {
        width: 200px;
        transform: translateX(-200px);
        transition: transform 0.3s ease;
    }

    .sidebar.open {
        transform: translateX(0);
    }

    .form-container {
        flex-direction: column;
    }

    .form-sidebar {
        order: -1;
    }

    .image-gallery-item {
        width: 80px;
        height: 80px;
    }
}