<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Groupscategories;
class Category extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function groupscategoryc()
    {
        return $this->hasMany(Groupscategories::class,'category_id');
    }


    public function groupscategory()
    {
        return $this->belongsToMany(Groupscategories::class, 'groupscategories','category_id','group_id');
    }

}
