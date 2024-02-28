<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Groups;
class Groupscategories extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function groupsc()
    {
        return $this->belongsTo(Groups::class, 'group_id', 'id');
    }

    public function group(){
        return $this->belongsTo(Groups::class, 'group_id');
    }
}
