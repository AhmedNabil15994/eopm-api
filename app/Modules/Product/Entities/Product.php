<?php

namespace Modules\Product\Entities;

use App\Traits\ScopesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use  SoftDeletes;
    use ScopesTrait;
    use HasFactory;

    protected $with = [];
    protected $guarded = ['id'];

}
