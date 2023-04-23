<?php

namespace Bidb97\CrossPosting\Models;

use Illuminate\Database\Eloquent\Model;

class CrossPosting extends Model
{
    /**
     * @var string
     */
    protected $table = 'cross_posting';

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

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected function setKeysForSaveQuery($query)
    {
        return $query->where([
            'model' => $this->model,
            'model_id' => $this->model_id
        ]);
    }
}
