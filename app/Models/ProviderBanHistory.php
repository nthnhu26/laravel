<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProviderBanHistory extends Model
{
    use HasFactory;

    protected $primaryKey = 'ban_id';
    protected $table = 'provider_ban_history';

    protected $fillable = ['provider_id', 'ban_reason'];


    public function provider()
    {
        return $this->belongsTo(ServiceProvider::class, 'provider_id');
    }

}