// public/js/location-image.js
let map, marker;

function initializeMap(config) {
    const {
        apiKey = '',
        maptilesKey = '',
        defaultLat = 9.8088,
        defaultLng = 106.5662,
        initialLat = null,
        initialLng = null
    } = config;

    console.log('Goong API Key:', apiKey);
    console.log('Goong Maptiles Key:', maptilesKey);

    if (typeof goongjs === 'undefined') {
        document.getElementById('map').style.display = 'none';
        document.getElementById('map-error').style.display = 'block';
        console.error('Thư viện Goong JS không được tải. Kiểm tra CDN.');
        return;
    }

    if (!maptilesKey) {
        document.getElementById('map').style.display = 'none';
        document.getElementById('map-error').style.display = 'block';
        console.error('Khóa Maptiles Goong bị thiếu.');
        return;
    }

    try {
        goongjs.accessToken = maptilesKey;
        const center = initialLat && initialLng ? [initialLng, initialLat] : [defaultLng, defaultLat];
        map = new goongjs.Map({
            container: 'map',
            style: '/api/goong/map-style', // Sử dụng proxy để tránh lỗi 403
            center: center,
            zoom: 12
        });

        if (initialLat && initialLng) {
            placeMarker({ lng: initialLng, lat: initialLat });
        }

        map.on('click', function(e) {
            placeMarker(e.lngLat);
        });

        map.on('error', function(e) {
            document.getElementById('map').style.display = 'none';
            document.getElementById('map-error').style.display = 'block';
            console.error('Lỗi tải bản đồ Goong:', e);
        });

        initializePlaceSearch(apiKey);
    } catch (error) {
        document.getElementById('map').style.display = 'none';
        document.getElementById('map-error').style.display = 'block';
        console.error('Lỗi khởi tạo bản đồ Goong:', error);
    }
}

function placeMarker(lngLat) {
    if (marker) {
        marker.setLngLat(lngLat);
    } else {
        marker = new goongjs.Marker({
            draggable: true
        })
            .setLngLat(lngLat)
            .addTo(map);

        marker.on('dragend', function() {
            updateCoordinates(marker.getLngLat());
        });
    }

    updateCoordinates(lngLat);
    map.panTo(lngLat);
}

function updateCoordinates(lngLat) {
    document.getElementById('latitude').value = lngLat.lat.toFixed(6);
    document.getElementById('longitude').value = lngLat.lng.toFixed(6);
}

function initializePlaceSearch(apiKey) {
    let typingTimer;
    const typingDelay = 500;

    const placeSearchInput = document.getElementById('place_search');
    if (!placeSearchInput) return;

    placeSearchInput.addEventListener('input', function() {
        clearTimeout(typingTimer);
        const query = this.value.trim();
        if (query.length < 3) {
            document.getElementById('place_suggestions').style.display = 'none';
            return;
        }

        typingTimer = setTimeout(() => {
            fetchPlaces(query, apiKey);
        }, typingDelay);
    });

    placeSearchInput.addEventListener('blur', function() {
        setTimeout(() => {
            document.getElementById('place_suggestions').style.display = 'none';
        }, 200);
    });
}

function fetchPlaces(query, apiKey) {
    if (!apiKey) {
        console.error('Khóa API Goong bị thiếu cho tìm kiếm địa điểm.');
        return;
    }

    const url = `/api/goong/autocomplete?input=${encodeURIComponent(query)}`;
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Lỗi HTTP! Trạng thái: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            displaySuggestions(data);
        })
        .catch(error => {
            console.error('Lỗi tìm kiếm địa điểm:', error);
            document.getElementById('place_suggestions').style.display = 'none';
        });
}

function displaySuggestions(data) {
    const suggestionsDiv = document.getElementById('place_suggestions');
    suggestionsDiv.innerHTML = '';
    if (data.predictions && data.predictions.length > 0) {
        data.predictions.forEach(place => {
            const item = document.createElement('div');
            item.className = 'list-group-item';
            item.textContent = place.description;
            item.dataset.placeId = place.place_id;
            item.addEventListener('click', () => selectPlace(place.place_id));
            suggestionsDiv.appendChild(item);
        });
        suggestionsDiv.style.display = 'block';
    } else {
        suggestionsDiv.innerHTML = '<div class="list-group-item">Không tìm thấy địa điểm.</div>';
        suggestionsDiv.style.display = 'block';
    }
}

function selectPlace(placeId) {
    const url = `/api/goong/place-detail?place_id=${placeId}`;
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Lỗi HTTP! Trạng thái: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.result && data.result.geometry && data.result.geometry.location) {
                const lngLat = {
                    lng: data.result.geometry.location.lng,
                    lat: data.result.geometry.location.lat
                };
                placeMarker(lngLat);
                document.getElementById('place_suggestions').style.display = 'none';
                document.getElementById('place_search').value = data.result.formatted_address;
                document.getElementById('address_vi').value = data.result.formatted_address;
                document.getElementById('address_en').value = data.result.formatted_address;
            }
        })
        .catch(error => {
            console.error('Lỗi lấy chi tiết địa điểm:', error);
        });
}

function initializeImagePreview() {
    const imageInput = document.getElementById('images');
    if (!imageInput) return;

    imageInput.addEventListener('change', function(event) {
        const previewContainer = document.getElementById('image-preview');
        previewContainer.innerHTML = '';

        if (this.files) {
            for (let i = 0; i < this.files.length; i++) {
                const file = this.files[i];
                const reader = new FileReader();

                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.classList.add('image-gallery-item');
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    div.appendChild(img);
                    previewContainer.appendChild(div);
                };

                reader.readAsDataURL(file);
            }
        }
    });
}

// Khởi tạo khi DOM sẵn sàng
document.addEventListener('DOMContentLoaded', function() {
    // Lấy API keys từ meta tags
    const goongApiKey = document.querySelector('meta[name="goong-api-key"]')?.content || '';
    const goongMaptilesKey = document.querySelector('meta[name="goong-maptiles-key"]')?.content || '';

    // Lấy tọa độ ban đầu từ meta tags (nếu có)
    const initialLat = document.querySelector('meta[name="initial-lat"]')?.content || null;
    const initialLng = document.querySelector('meta[name="initial-lng"]')?.content || null;

    // Khởi tạo bản đồ
    initializeMap({
        apiKey: goongApiKey,
        maptilesKey: goongMaptilesKey,
        defaultLat: 9.8088,
        defaultLng: 106.5662,
        initialLat: initialLat ? parseFloat(initialLat) : null,
        initialLng: initialLng ? parseFloat(initialLng) : null
    });

    // Khởi tạo xem trước hình ảnh
    initializeImagePreview();
});