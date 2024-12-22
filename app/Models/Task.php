<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'theme',
        'field_study',
        'research_type',
        'title',
        'research_method',
        'abstract',
        'user_id',
        'assistant_id',
        'thread_id',
        'status'
    ];

    // Research paper types
    public static $researchTypes = [
        'empirical' => 'Empirical Research',
        'review' => 'Literature Review',
        'case_study' => 'Case Study',
        'theoretical' => 'Theoretical Research',
        'experimental' => 'Experimental Research',
        'qualitative' => 'Qualitative Research',
        'quantitative' => 'Quantitative Research',
        'mixed_methods' => 'Mixed Methods Research'
    ];

    // Research methods
    public static $researchMethods = [
        'survey' => 'Survey Research',
        'interview' => 'Interviews',
        'observation' => 'Observation',
        'experiment' => 'Experimental',
        'content_analysis' => 'Content Analysis',
        'statistical' => 'Statistical Analysis',
        'ethnography' => 'Ethnography',
        'grounded_theory' => 'Grounded Theory',
        'case_analysis' => 'Case Analysis',
        'meta_analysis' => 'Meta-Analysis'
    ];

    // Task statuses
    public static $statuses = [
        'draft' => 'Draft',
        'in_progress' => 'In Progress',
        'review' => 'Under Review',
        'revision' => 'Needs Revision',
        'completed' => 'Completed'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assistant()
    {
        return $this->belongsTo(Assistant::class);
    }
}
