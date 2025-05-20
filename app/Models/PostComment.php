<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostComment extends Model
{
    protected $table = 'post_comments';
    protected $primaryKey = 'post_comment_id';

    protected $fillable = [
        'post_id',
        'user_id',
        'parent_id',
        'content',
        'status',
        'rejection_reason'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(PostComment::class, 'parent_id', 'post_comment_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(PostComment::class, 'parent_id', 'post_comment_id')
            ->where('status', 'approved')
            ->orderBy('created_at', 'asc');
    }
    public function isRootComment()
    {
        return $this->parent_id === null;
    }
    // Thêm phương thức để kiểm tra cấp độ của bình luận
    public function getLevel(): int
    {
        if (!$this->parent_id) {
            return 1; // Bình luận gốc
        }

        $parent = $this->parent;
        if (!$parent->parent_id) {
            return 2; // Bình luận cấp 2
        }

        return 3; // Bình luận cấp 3
    }

    // Kiểm tra xem bình luận có thể trả lời không (chỉ cho phép đến cấp 3)
    public function canReply(): bool
    {
        return $this->getLevel() < 3;
    }
}
