<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;

    protected $primaryKey = 'user_preference_id';
    protected $table = 'user_preferences';

    protected $fillable = ['user_id', 'preference_type_id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function preferenceType()
    {
        return $this->belongsTo(PreferenceType::class, 'preference_type_id');
    }
}