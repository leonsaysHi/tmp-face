<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Search extends Model
{
    protected $hidden = ["updated_at", "user_id"];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'filters' => 'array',
        'is_saved' => 'boolean',
        'include_active' => 'boolean',
        'include_cancelled' => 'boolean',
        'include_completed' => 'boolean',
        'include_draft' => 'boolean',
        'include_pending' => 'boolean',
        'is_advanced' => 'boolean',
    ];

    protected $fillable = [
        "basic_search_text",
        "filters",
        "include_draft",
        "include_pending",
        "include_cancelled",
        "include_active",
        "include_completed",
        "project_start_year_search_text",
        "project_name_search_text",
        "full_text_search_text",
        "project_description_search_text",
        "project_owner_search_text",
        "start_date_search_begin",
        "start_date_search_end",
        "end_date_search_begin",
        "end_date_search_end",
    ];

    /**
     * A search history belongs to a user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
