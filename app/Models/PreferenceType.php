<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PreferenceType extends Model
{
    use HasFactory;

    protected $primaryKey = 'preference_type_id';
    protected $table = 'preference_types';

    public $translatable = ['name', 'description'];

    protected $fillable = ['name', 'description'];


    public function userPreferences()
    {
        return $this->hasMany(UserPreference::class, 'preference_type_id');
    }
}