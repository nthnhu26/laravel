<div class="form-actions">
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-save me-1"></i> Lưu
    </button>
    <button type="button" class="btn btn-primary" name="save_continue" value="1" onclick="document.getElementById('{{ $formId }}').querySelector('#continue').value = '1'; document.getElementById('{{ $formId }}').submit();">
        <i class="fas fa-save me-1"></i> Lưu và tiếp tục
    </button>
    <a href="{{ route('admin.' . $viewPrefix . '.index') }}" class="btn btn-secondary">
        <i class="fas fa-times me-1"></i> Hủy
    </a>
    <input type="hidden" name="continue" id="continue" value="0">
</div>