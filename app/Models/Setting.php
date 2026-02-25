<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = ['key', 'value', 'group'];

    protected $casts = [
        'value' => 'array'
    ];

    public static function getValue(string $key, $default = null)
    {
        $s = static::where('key', $key)->first();
        if (!$s) return $default;
        return $s->value ?? $default;
    }

    public static function setValue(string $key, $value, ?string $group = null)
    {
        return static::updateOrCreate(['key' => $key], ['value' => $value, 'group' => $group]);
    }
}
