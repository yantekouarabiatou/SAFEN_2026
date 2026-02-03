<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class DishVendor extends Pivot
{
    protected $table = 'dish_vendor';

    protected $casts = [
        'price' => 'decimal:2',
        'available' => 'boolean'
    ];
}
