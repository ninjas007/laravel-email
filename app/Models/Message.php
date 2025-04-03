<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'subject',
        'list_id',
        'template_id',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        if (Auth::check() && Auth::user()->role == 'user') {
            $prefix = Auth::user()->tbl_prefix;
            $this->setTable($prefix . '_messages');
        }

        return 'messages';
    }

    public function contact_list()
    {
        return $this->belongsTo(ContactList::class, 'list_id', 'id');
    }

    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id', 'id');
    }
}
