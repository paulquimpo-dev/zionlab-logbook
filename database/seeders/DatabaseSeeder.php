<?php
namespace Database\Seeders;
use App\Models\{GradeLevel,Subject,UsageReason};
use Illuminate\Database\Seeder;
class DatabaseSeeder extends Seeder { public function run(): void { foreach(['Kindergarten','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6','Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12'] as $n) GradeLevel::firstOrCreate(['name'=>$n]); foreach(['Mathematics','Science','English','Filipino','Araling Panlipunan','ICT','Other'] as $n) Subject::firstOrCreate(['name'=>$n]); foreach(['Research','Assignment','Online Learning','Programming / Coding','School Project','Printing','Presentation','Other'] as $n) UsageReason::firstOrCreate(['name'=>$n]); } }
