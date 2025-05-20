@extends('layouts.admin')

@section('title', isset($post) ? 'Chỉnh sửa bài viết' : 'Thêm bài viết mới')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<style>
    .image-preview {
        position: relative;
        margin-bottom: 1rem;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .image-preview img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }
    
    .image-actions {
        position: absolute;
        top: 0;
        right: 0;
        display: flex;
        gap: 0.25rem;
        padding: 0.5rem;
        background-color: rgba(0, 0, 0, 0.5);
        border-bottom-left-radius: 0.5rem;
    }
    
    .image-actions button {
        width: 30px;
        height: 30px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .featured-badge {
        position: absolute;
        top: 0;
        left: 0;
        padding: 0.25rem 0.5rem;
        background-color: rgba(0, 120, 212, 0.8);
        color: white;
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .image-caption {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 0.5rem;
        background-color: rgba(0, 0, 0, 0.5);
        color: white;
        font-size: 0.85rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    
    .dropzone {
        border: 2px dashed #dee2e6;
        border-radius: 0.5rem;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .dropzone:hover {
        border-color: #0078D4;
    }
    
    .dropzone i {
        font-size: 2rem;
        color: #6c757d;
        margin-bottom: 1rem;
    }
    
    .note-editor {
        border-radius: 0.375rem;
    }
    
    .note-toolbar {
        background-color: #f8f9fa;
        border-top-left-radius: 0.375rem;
        border-top-right-radius: 0.375rem;
    }
    
    .bootstrap-tagsinput {
        width: 100%;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #212529;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        appearance: none;
        border-radius: 0.375rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
    }
    
    .bootstrap-tagsinput .tag {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        margin-right: 0.25rem;
        margin-bottom: 0.25rem;
        font-size: 0.875rem;
        font-weight: 600;
        line-height: 1;
        color: #fff;
        background-color: #0078D4;
        border-radius: 0.25rem;
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0">{{ isset($post) ? 'Chỉnh sửa bài viết' : 'Thêm bài viết mới' }}</h1>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    <form action="{{ isset($post) ? route('admin.posts.update', $post->post_id) : route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($post))
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Thông tin bài viết</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" 
                                value="{{ old('title', $post->title ?? '') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="short_description" class="form-label">Mô tả ngắn</label>
                            <textarea class="form-control @error('short_description') is-invalid @enderror" id="short_description" name="short_description" 
                                rows="3">{{ old('short_description', $post->short_description ?? '') }}</textarea>
                            @error('short_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Nội dung <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" 
                                rows="10">{{ old('content', $post->content ?? '') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Hình ảnh</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            @if(isset($post) && $post->images->isNotEmpty())
                                @foreach($post->images as $image)
                                    <div class="col-md-3 mb-3">
                                        <div class="image-preview">
                                            <img src="{{ asset('storage/' . $image->url) }}" alt="{{ $post->title }}">
                                            @if($image->is_featured)
                                                <div class="featured-badge">Ảnh đại diện</div>
                                            @endif
                                            @if($image->caption)
                                                <div class="image-caption">{{ $image->caption }}</div>
                                            @endif
                                            <div class="image-actions">
                                                <button type="button" class="btn btn-sm btn-light" 
                                                    data-bs-toggle="modal" data-bs-target="#editImageModal{{ $image->image_id }}" title="Chỉnh sửa">
                                                    <i class="bi bi-pencil"></i>
                                                </button>
                                                @if(!$image->is_featured)
                                                    <form action="{{ route('admin.posts.setFeaturedImage', [$post->post_id, $image->image_id]) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-primary" title="Đặt làm ảnh đại diện">
                                                            <i class="bi bi-star"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                <form action="{{ route('admin.posts.removeImage', [$post->post_id, $image->image_id]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" title="Xóa ảnh">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>

                                        <!-- Modal chỉnh sửa ảnh -->
                                        <div class="modal fade" id="editImageModal{{ $image->image_id }}" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Chỉnh sửa thông tin ảnh</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('admin.posts.updateImageCaption', [$post->post_id, $image->image_id]) }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <div class="mb-3">
                                                                <label for="caption{{ $image->image_id }}" class="form-label">Chú thích ảnh</label>
                                                                <textarea class="form-control" id="caption{{ $image->image_id }}" name="caption" rows="3">{{ $image->caption }}</textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                                            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="dropzone" id="imageDropzone">
                            <input type="file" name="images[]" id="images" multiple accept="image/*" class="d-none">
                            <i class="bi bi-cloud-arrow-up"></i>
                            <p class="mb-0">Kéo thả ảnh vào đây hoặc nhấp để chọn ảnh</p>
                            <p class="text-muted small">Hỗ trợ định dạng: JPG, PNG, GIF. Tối đa 5MB mỗi ảnh.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Xuất bản</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                                <option value="draft" {{ old('status', $post->status ?? '') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                                <option value="published" {{ old('status', $post->status ?? '') == 'published' ? 'selected' : '' }}>Xuất bản</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save me-1"></i> {{ isset($post) ? 'Cập nhật' : 'Lưu' }} bài viết
                            </button>
                            @if(isset($post))
                                <a href="{{ route('posts.show', $post->post_id) }}" class="btn btn-outline-primary" target="_blank">
                                    <i class="bi bi-eye me-1"></i> Xem bài viết
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Phân loại</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="topic" class="form-label">Chủ đề</label>
                            <select class="form-select @error('topic') is-invalid @enderror" id="topic" name="topic">
                                <option value="travel_tips" {{ old('topic', $post->topic ?? '') == 'travel_tips' ? 'selected' : '' }}>Mẹo du lịch</option>
                                <option value="local_experience" {{ old('topic', $post->topic ?? '') == 'local_experience' ? 'selected' : '' }}>Trải nghiệm địa phương</option>
                                <option value="food" {{ old('topic', $post->topic ?? '') == 'food' ? 'selected' : '' }}>Ẩm thực</option>
                                <option value="accommodation" {{ old('topic', $post->topic ?? '') == 'accommodation' ? 'selected' : '' }}>Chỗ ở</option>
                                <option value="transportation" {{ old('topic', $post->topic ?? '') == 'transportation' ? 'selected' : '' }}>Phương tiện</option>
                                <option value="event" {{ old('topic', $post->topic ?? '') == 'event' ? 'selected' : '' }}>Sự kiện</option>
                                <option value="culture" {{ old('topic', $post->topic ?? '') == 'culture' ? 'selected' : '' }}>Văn hóa</option>
                                <option value="history" {{ old('topic', $post->topic ?? '') == 'history' ? 'selected' : '' }}>Lịch sử</option>
                                <option value="activity" {{ old('topic', $post->topic ?? '') == 'activity' ? 'selected' : '' }}>Hoạt động</option>
                                <option value="other" {{ old('topic', $post->topic ?? '') == 'other' ? 'selected' : '' }}>Khác</option>
                            </select>
                            @error('topic')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="tags" class="form-label">Tags</label>
                            <input type="text" class="form-control @error('tags') is-invalid @enderror" id="tags" name="tags" 
                                value="{{ old('tags', $post->tags ?? '') }}" data-role="tagsinput">
                            <div class="form-text">Nhập tag và nhấn Enter để thêm</div>
                            @error('tags')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Liên kết</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="attraction_id" class="form-label">Địa điểm liên quan</label>
                            <select class="form-select @error('attraction_id') is-invalid @enderror" id="attraction_id" name="attraction_id">
                                <option value="">-- Chọn địa điểm --</option>
                                @foreach($attractions as $attraction)
                                    <option value="{{ $attraction->attraction_id }}" {{ old('attraction_id', $post->attraction_id ?? '') == $attraction->attraction_id ? 'selected' : '' }}>
                                        {{ $attraction->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('attraction_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-tagsinput@0.7.1/dist/bootstrap-tagsinput.min.js"></script>
<script>
    $(document).ready(function() {
        // Summernote
        $('#content').summernote({
            height: 400,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link', 'picture']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ],
            callbacks: {
                onImageUpload: function(files) {
                    for (let i = 0; i < files.length; i++) {
                        uploadSummernoteImage(files[i]);
                    }
                }
            }
        });

        // Upload image for Summernote
        function uploadSummernoteImage(file) {
            let formData = new FormData();
            formData.append('image', file);
            formData.append('_token', '{{ csrf_token() }}');

            $.ajax({
                url: '{{ route('admin.upload.image') }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(data) {
                    $('#content').summernote('insertImage', data.url);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error(textStatus + ': ' + errorThrown);
                    alert('Không thể tải lên hình ảnh. Vui lòng thử lại.');
                }
            });
        }

        // Image dropzone
        const dropzone = document.getElementById('imageDropzone');
        const fileInput = document.getElementById('images');

        dropzone.addEventListener('click', () => {
            fileInput.click();
        });

        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('border-primary');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('border-primary');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('border-primary');
            fileInput.files = e.dataTransfer.files;
        });

        // Tags input
        $('#tags').tagsinput({
            trimValue: true,
            confirmKeys: [13, 44, 32], // Enter, comma, space
            maxTags: 10
        });
    });
</script>
@endsection