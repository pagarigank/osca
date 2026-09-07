<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class QuadParameter extends Model
{
    protected $fillable = ['quad_type', 'name', 'points', 'age_from', 'age_to', 'is_active', 'created_by', 'updated_by'];
    protected static function booted() {
        static::creating(fn($m) => $m->created_by = auth()->id() ?? 1);
        static::updating(fn($m) => $m->updated_by = auth()->id() ?? 1);
    }

    public function scopeQuad1($q) { return $q->where('quad_type', 'QUAD1'); }
    public function scopeQuad2($q) { return $q->where('quad_type', 'QUAD2'); }
    public function scopeQuad3($q) { return $q->where('quad_type', 'QUAD3'); }
    public function scopeQuad4($q) { return $q->where('quad_type', 'QUAD4'); }
}
