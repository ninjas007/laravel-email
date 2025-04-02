<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'background_color',
        'body_template',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (Auth::check() && Auth::user()->role == 'user') {
            $prefix = Auth::user()->tbl_prefix;
            $this->setTable($prefix . '_templates');
        }

        return 'templates';
    }
}
