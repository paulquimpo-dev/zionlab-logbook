<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class LaboratorySession extends Model { protected $fillable=['computer_id','first_name','last_name','student_id','grade_level_id','subject_id','usage_reason_id','other_reason_details','time_in','time_out','duration_seconds','status','resolution']; protected $casts=['time_in'=>'datetime','time_out'=>'datetime']; public function computer(){return $this->belongsTo(Computer::class);} public function gradeLevel(){return $this->belongsTo(GradeLevel::class);} public function subject(){return $this->belongsTo(Subject::class);} public function usageReason(){return $this->belongsTo(UsageReason::class);} }
