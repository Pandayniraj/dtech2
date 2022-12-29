<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Seld\PharUtils\Timestamps;

class CostTerm extends Model
{
    use HasFactory;
    protected $table="costterms";
    protected $fillable = ['name', 'dr_ledger_id', 'cr_ledger_id'];
    public $timestamps = false;
}
