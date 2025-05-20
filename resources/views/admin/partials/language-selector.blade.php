<div class="language-selector">
    <h4>Ngôn ngữ</h4>
    <div class="language-item {{ app()->getLocale() == 'vi' ? 'active' : '' }}" data-lang="vi" onclick="switchLanguage('vi')">
        <img src="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/flags/4x3/vn.svg" alt="Tiếng Việt">
        <span class="lang-label">Tiếng Việt {{ app()->getLocale() == 'vi' ? '(Đang chỉnh sửa)' : '' }}</span>
    </div>
    <div class="language-item {{ app()->getLocale() == 'en' ? 'active' : '' }}" data-lang="en" onclick="switchLanguage('en')">
        <img src="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/3.5.0/flags/4x3/us.svg" alt="English">
        <span class="lang-label">English {{ app()->getLocale() == 'en' ? '(Đang chỉnh sửa)' : '' }}</span>
    </div>
</div>