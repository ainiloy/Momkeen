<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Groups;
use App\Models\User;
class Groupsusers extends Model
{
    public  $table = 'groupsusers';
    use HasFactory;
    protected $guarded = [];
    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function group(){
        return $this->belongsTo(Groups::class, 'groups_id');
    }
}
