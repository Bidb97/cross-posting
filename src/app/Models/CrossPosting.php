<?php

namespace Bidb97\CrossPosting\Models;

use Illuminate\Database\Eloquent\Model;

class CrossPosting extends Model
{
    /**
     * @var string
     */
    protected $table = 'bidb97_cross_posting';

    /**
     * @var string[]
     */
    protected $primaryKey = ['model', 'model_id'];

    /**
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var string[]
     */
    protected $fillable = [
        'model',
        'model_id',
        'posting_data',
        'resource_uri',
        'short_uri',
        'publish_date',
        'is_posted'
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'posting_data' => 'array'
    ];
}
