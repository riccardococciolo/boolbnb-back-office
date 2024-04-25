<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Apartment extends Model
{
    use HasFactory;

    use SoftDeletes;

    public $fillable = ['title', 'slug', 'price', 'address', 'latitude', 'longitude', 'dimension_mq', 'rooms_number', 'beds_number', 'bathrooms_number', 'is_visible'];

    public function setTitleAttribute($_title) {
        $this->attributes['title'] = $_title;
        $this->attributes['slug']  = Str::slug($_title);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function services() {
        return $this->belongsToMany(Service::class);
    }

    public function sponsors() {
        return $this->belongsToMany(Sponsor::class)->withPivot('expiration_date');;
    }

    public function images() {
        return $this->hasMany(Image::class);
    }

    public function leads() {
        return $this->hasMany(Lead::class);
    }

    public function views() {
        return $this->hasMany(View::class);
    }

    public static function calcCoordinates($longitude, $latitude, $radius = 20)
    {
        $lng_min = $longitude - $radius / abs(cos(deg2rad($latitude)) * 69);
        $lng_max = $longitude + $radius / abs(cos(deg2rad($latitude)) * 69);
        $lat_min = $latitude - ($radius / 69);
        $lat_max = $latitude + ($radius / 69);

        return [
            'min' => [
                'lat' => $lat_min,
                'lng' => $lng_min,
            ],
            'max' => [
                'lat' => $lat_max,
                'lng' => $lng_max,
            ],
        ];
    }

}
