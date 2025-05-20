<!-- resources/views/components/images-gallery.blade.php -->
<div class="gallery row g-3">
    @forelse ($images as $image)
        <div class="col-6 col-md-3">
            <img src="{{ asset('storage/' . $image->url) }}" alt="{{ $caption ?? 'Image' }}" class="w-100 rounded">
        </div>
    @empty
        <div class="text-center py-5">
            <i class="bi bi-image text-muted display-3"></i>
            <p class="mt-3 text-muted fs-5">Chưa có hình ảnh nào.</p>
        </div>
    @endforelse
</div>