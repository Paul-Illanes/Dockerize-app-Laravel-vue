<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CmsParameter extends Model
{
    //use \Rutorika\Sortable\SortableTrait;

    protected $table = 'cms_parameters';
    protected $fillable = ['group_id', 'parent_id', 'alias', 'value', 'metadata', 'active'];
    protected static $sortableField = 'position';
    protected static $sortableGroupField = 'group_id';

    public function setMetadataAttribute($value)
    {
        $this->attributes['metadata'] = json_encode($value);
    }

    public function getMetadataAttribute($value)
    {
        return json_decode($value, true);
    }

    public function from_group($alias)
    {
        return $this->hasMany('App\Models\CmsParameter', 'group_id', 'id')
            ->whereIn(
                'group_id',
                \App\Models\CmsParameterGroup::select('id')
                    ->where('alias', $alias)->get()->toArray()
            )
            ->where('active', '1')
            ->orderBy('position');
    }

    public function lang($lang_id = 1)
    {
        return $this->hasOne('App\CmsParameterLang', 'parameter_id')
            ->where('lang_id', $lang_id);
    }

    public function parent()
    {
        return $this->belongsTo('App\Models\CmsParameterAlias', 'parent_id');
    }

    public function group()
    {
        return $this->belongsTo('App\Models\CmsParameterGroup', 'group_id');
    }

    public function children()
    {
        return $this->hasMany('App\Models\CmsParameterAlias', 'parent_id', 'id')
            ->where('active', '1')
            ->orderBy('position');
    }
}
