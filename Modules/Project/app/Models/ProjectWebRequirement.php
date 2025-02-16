<?php

namespace Modules\Project\app\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProjectWebRequirement extends Model
{
    use HasFactory;

    protected $casts = [
        'internal_pages_content' => 'json',
        'contract_differences' => 'json',
    ];

    protected $fillable = [
        'representative',
        'color_scheme',
        'similar_websites',
        'preferred_websites',
        'site_title',
        'design_based_on',
        'menu_titles',
        'homepage_layout',
        'website_features',
        'internal_pages_content',
        'contract_differences',
        'final_decision',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(ProjectWeb::class);
    }
}
