<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Groupsusers;
use App\Models\Groupscategories;
class Groups extends Model
{
    use HasFactory;
    protected $table = 'groups';
    protected $guarded = [];
    public function groupsusers()
    {
        return $this->belongsToMany(Groupsusers::class,'groupsusers','groups_id','users_id');
    }

    public function groupsuser()
    {
        return $this->hasMany(Groupsusers::class, 'groups_id');
    }
    public function groupcategory()
    {
        return $this->hasMany(Groupscategories::class, 'category_id');
    }
}
