<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Listing extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'address',
        'sqft',
        'wifi_speed',
        'max_person',
        'price_per_day',
        'attachments',
        'full_support_available',
        'gym_area_available',
        'mini_cafe_available',
        'cinema_available',
    ];

    protected $casts = [
        'attachments'=> 'array',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }
    
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }
    
    /**
     * Get all of the transaction for the Listing
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transaction(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}
