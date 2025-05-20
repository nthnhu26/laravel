/**
 * Xử lý chức năng yêu thích địa điểm
 */
document.addEventListener("DOMContentLoaded", () => {
    // Lấy tất cả các nút yêu thích
    const favoriteButtons = document.querySelectorAll(".card-favorite")
  
    // Thêm sự kiện click cho mỗi nút
    favoriteButtons.forEach((button) => {
      button.addEventListener("click", function (e) {
        e.preventDefault()
        e.stopPropagation()
  
        const placeId = this.getAttribute("data-place-id")
        toggleFavorite(this)
      })
    })
  })
  
  /**
   * Hàm xử lý thêm/xóa địa điểm yêu thích
   */
  // Reemplaza el script actual por este
function toggleFavorite(element) {
    // Detener la propagación del evento para evitar que se active en elementos padres
    event.preventDefault();
    event.stopPropagation();
    
    const placeId = element.getAttribute('data-place-id');
    const isActive = element.classList.contains('active');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch('/places/favorite', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({ place_id: placeId })
    })
    .then(response => {
        if (!response.ok) {
            if (response.status === 401) {
                window.location.href = '/login';
                throw new Error('Vui lòng đăng nhập để thêm địa điểm vào danh sách yêu thích.');
            }
            throw new Error('Đã có lỗi xảy ra.');
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            alert(data.error);
            return;
        }

        if (data.status === 'added') {
            element.classList.add('active');
            element.querySelector('i').classList.remove('far');
            element.querySelector('i').classList.add('fas');
            
            // Mostrar mensaje de éxito
            alert(data.message);
        } else {
            element.classList.remove('active');
            element.querySelector('i').classList.add('far');
            element.querySelector('i').classList.remove('fas');
            
            // Mostrar mensaje de éxito
            alert(data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert(error.message || 'Đã có lỗi xảy ra. Vui lòng thử lại.');
    });
}
  
  /**
   * Hiển thị thông báo toast
   */
  function showToast(title, message, type = "info") {
    // Kiểm tra xem đã có container toast chưa
    let toastContainer = document.querySelector(".toast-container")
  
    if (!toastContainer) {
      toastContainer = document.createElement("div")
      toastContainer.className = "toast-container position-fixed bottom-0 end-0 p-3"
      document.body.appendChild(toastContainer)
    }
  
    // Tạo toast mới
    const toastId = "toast-" + Date.now()
    const toastEl = document.createElement("div")
    toastEl.className = `toast align-items-center text-white bg-${type === "success" ? "success" : type === "error" ? "danger" : "primary"} border-0`
    toastEl.id = toastId
    toastEl.setAttribute("role", "alert")
    toastEl.setAttribute("aria-live", "assertive")
    toastEl.setAttribute("aria-atomic", "true")
  
    toastEl.innerHTML = `
          <div class="d-flex">
              <div class="toast-body">
                  <strong>${title}</strong>: ${message}
              </div>
              <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
          </div>
      `
  
    toastContainer.appendChild(toastEl)
  
    // Hiển thị toast
    const bootstrap = window.bootstrap
    const toast = new bootstrap.Toast(toastEl, {
      autohide: true,
      delay: 3000,
    })
    toast.show()
  
    // Xóa toast sau khi ẩn
    toastEl.addEventListener("hidden.bs.toast", () => {
      toastEl.remove()
    })
  }