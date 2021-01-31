<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EmailAttachment extends Model
{
    use HasFactory;

    protected $fillable = ['attachment', 'name'];

    public function getAttachmentAttribute($value)
    {
        return Storage::url($value);
    }
}
