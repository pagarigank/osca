<?php
// ─── Frailty ──────────────────────────────────────────────────────────────────
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Frailty extends Model
{
    protected $fillable = ['name', 'is_active', 'created_by', 'updated_by'];
    protected static function booted() {
        static::creating(fn($m) => $m->created_by = auth()->id() ?? 1);
        static::updating(fn($m) => $m->updated_by = auth()->id() ?? 1);
    }
}
