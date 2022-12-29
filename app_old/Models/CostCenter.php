<?php

namespace App\Models;

use Auth;
use Illuminate\Database\Eloquent\Model;

class CostCenter extends Model
{
    /**
     * @var array
     */
    protected $table = 'cost_center';

    /**
     * @var array
     */
    protected $fillable = ['name', 'description', 'order_details', 'owner_id'];

    public function owner()
    {
        return $this->belongsTo(\App\User::class, 'owner_id');
    }

    /**
     * @return bool
     */
    public function isEditable()
    {
        // Protect the admins and users Intakes from editing changes
        if (('admins' == $this->name) || ('users' == $this->name)) {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     */
    public function isDeletable()
    {
        // Protect the admins and users Intakes from deletion
        if (('admins' == $this->name) || ('users' == $this->name)) {
            return false;
        }

        return true;
    }

    public function hasPerm(Permission $perm)
    {
        // perm 'basic-authenticated' is always checked.
        if ('basic-authenticated' == $perm->name) {
            return true;
        }
        // Return true if the Intake has is assigned the given permission.
        if ($this->perms()->where('id', $perm->id)->first()) {
            return true;
        }
        // Otherwise
        return false;
    }
}
