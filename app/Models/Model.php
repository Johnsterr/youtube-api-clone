<?php

namespace App\Models;

use App\Traits\WithRelationships;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Arr;

class Model extends BaseModel
{
    use WithRelationships;
}
