<?php

namespace App\Models;

use App\Traits\HasImages;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Post extends Model
{
    use HasTranslations, HasImages;

    protected $table = 'posts';
    protected $primaryKey = 'post_id';



    protected $fillable = [
        'title',
        'content',
        'author_id',
        'topic',
        'attraction_id',
        'status',
        'rejection_reason',
        'tags',
        'short_description',
        'views'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id', 'user_id');
    }

    public function attraction(): BelongsTo
    {
        return $this->belongsTo(Attraction::class, 'attraction_id', 'attraction_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(PostComment::class, 'post_id', 'post_id')
            ->whereNull('parent_id')
            ->where('status', 'approved')
            ->orderBy('created_at', 'desc');
    }

    public function allComments(): HasMany
    {
        return $this->hasMany(PostComment::class, 'post_id', 'post_id');
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }

    public function getTopicLabelAttribute(): string
    {
        return match ($this->topic) {
            'travel_tips' => 'Mẹo du lịch',
            'local_experience' => 'Trải nghiệm địa phương',
            'food' => 'Ẩm thực',
            'accommodation' => 'Chỗ ở',
            'transportation' => 'Phương tiện',
            'event' => 'Sự kiện',
            'culture' => 'Văn hóa',
            'history' => 'Lịch sử',
            'activity' => 'Hoạt động',
            default => 'Khác',
        };
    }

    public function getTagsArrayAttribute(): array
    {
        if (empty($this->tags)) {
            return [];
        }
        
        return array_map('trim', explode(',', $this->tags));
    }
}