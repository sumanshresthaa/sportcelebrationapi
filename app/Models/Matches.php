<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matches extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'title',
        'date',
        'score_germany',
        'score_france',
    ];

    // Relationship: each match belongs to one country
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
