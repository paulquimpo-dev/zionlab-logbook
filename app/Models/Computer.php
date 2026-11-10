<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Computer extends Model { protected $fillable=['pc_number','station_token_hash','active','last_seen_at']; protected $casts=['active'=>'boolean','last_seen_at'=>'datetime']; public function sessions(){return $this->hasMany(LaboratorySession::class);} }
