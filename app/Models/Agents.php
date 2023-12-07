<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agents extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $primaryKey = 'agent_id';
    protected $fillable = [
        'agent_name',
        'agent_nickname',
        'agent_size',
        'agent_birthdate',
        'agent_gender',
    ];
    protected $table = 'agents';
}
