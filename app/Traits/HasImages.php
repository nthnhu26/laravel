<?php

namespace App\Traits;

use App\Models\Image;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasImages
{
    /**
     * Get all images for this model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable', 'entity_type', 'entity_id');
    }

    /**
     * Get the featured image for this model.
     *
     * @return \App\Models\Image|null
     */
    public function getFeaturedImageAttribute()
    {
        return $this->images()->where('is_featured', true)->first() 
            ?? $this->images()->first();
    }

    /**
     * Add an image to this model.
     *
     * @param string $url
     * @param string|array|null $caption
     * @param bool $isFeatured
     * @return \App\Models\Image
     */
    public function addImage(string $url, $caption = null, bool $isFeatured = false): Image
    {
        return $this->images()->create([
            'url' => $url,
            'caption' => $caption,
            'is_featured' => $isFeatured,
        ]);
    }

    /**
     * Set an image as featured and unset others.
     *
     * @param int $imageId
     * @return bool
     */
    public function setFeaturedImage(int $imageId): bool
    {
        // First, unset all featured images
        $this->images()->update(['is_featured' => false]);
        
        // Then set the specified image as featured
        return $this->images()->where('image_id', $imageId)->update(['is_featured' => true]);
    }

    /**
     * Remove an image from this model.
     *
     * @param int $imageId
     * @return bool
     */
    public function removeImage(int $imageId): bool
    {
        return $this->images()->where('image_id', $imageId)->delete();
    }

    /**
     * Update image caption.
     *
     * @param int $imageId
     * @param string|array $caption
     * @return bool
     */
    public function updateImageCaption(int $imageId, $caption): bool
    {
        return $this->images()->where('image_id', $imageId)->update(['caption' => $caption]);
    }
}