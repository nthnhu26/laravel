<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ServiceProvider extends Model
{
    use HasFactory, HasTranslations;

    protected $primaryKey = 'provider_id';
    protected $table = 'service_providers';

    public $translatable = ['name', 'description', 'address'];
    
    protected $fillable = [
        'user_id', 'name', 'description', 'address', 'phone', 'email',
        'website', 'logo', 'approval_status', 'license_number', 'license_file', 'status'
    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function hotels()
    {
        return $this->hasMany(Hotel::class, 'provider_id');
    }

    public function restaurants()
    {
        return $this->hasMany(Restaurant::class, 'provider_id');
    }

    public function tours()
    {
        return $this->hasMany(Tour::class, 'provider_id');
    }

    public function transports()
    {
        return $this->hasMany(Transport::class, 'provider_id');
    }

    public function banHistory()
    {
        return $this->hasMany(ProviderBanHistory::class, 'provider_id');
    }
}