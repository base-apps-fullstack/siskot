<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Model\{
    ColumnFilterer, ColumnSorter, FindBy
};

class Menu extends Model
{
    use ColumnFilterer;
    use ColumnSorter;
    use FindBy;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'menus';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'parent_id',
        'name',
        'classification',
        'icon',
        'url',
        'type',
        'actions',
        'order_classification',
        'order_inner_classification',
        'is_collapse'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'actions' => 'array',
        'is_collapse' => 'boolean',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    /**
     * Get the parent that owns the area.
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public static function getAllParentId($id, $includeCurrentId = true, array &$parentIds = [])
    {
        if ($id instanceof \Illuminate\Support\Collection) {
            $id = $id->toArray();
        }

        $current = self::select('parent_id')->whereNotNull('parent_id')->groupBy('parent_id');
        $current = (
            is_array($id) ?
                $current->whereIn('id', $id) :
                $current->where('id', $id)
        );

        if ($includeCurrentId) {
            if (is_array($id)) {
                $parentIds = array_merge($id, $parentIds);
            } else {
                $parentIds[] = $id;
            }
        }

        if ($current->count()) {
            $currentIds = $current->pluck('parent_id')->toArray();
            $parentIds = array_merge($currentIds, $parentIds);
            self::getAllParentId($currentIds, false, $parentIds);
        }

        return $parentIds;
    }

    public static function getAreaParentTree($id, $level_desc = null)
    {
        $query = self::whereIn('id', self::getAllParentId($id));

        if (is_null($level_desc) == false) {
            return $query->where('level_desc', $level_desc)->first();
        }

        return $query->get();
    }

    /**
     * Get the children for the area.
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public static function getAllChildrenId($parentId, $includeCurrentId = true, array &$childrenIds = [], $self_area = [])
    {
        if ($parentId instanceof \Illuminate\Support\Collection) {
            $parentId = $parentId->toArray();
        }
        
        if (count($self_area) > 0) {
            $children = self::select('id')->with('children')->whereIn('id', $self_area);
        } else {
            $children = self::select('id')->with('children');
        }

        $children = is_array($parentId)
            ? $children->whereIn('parent_id', $parentId)
            : $children->where('parent_id', $parentId);

        if ($includeCurrentId) :
            if (is_array($parentId)) :
                $childrenIds = array_merge($childrenIds, $parentId);
            else :
                $childrenIds[] = $parentId;
            endif;
        endif;

        if ($children->count()) :
            $currentIds = $children->pluck('id')->toArray();
            $childrenIds = array_merge($childrenIds, $currentIds);
            self::getAllChildrenId($currentIds, false, $childrenIds, $self_area);
        endif;
        
        return $childrenIds;
    }

    public static function getParentTreeArea(int $id, array &$data = []): \Illuminate\Support\Optional
    {
        $current = self::where('id', $id)->first();

        if ($current instanceof Area) {
            $data[$current->level_desc] = (object) [
                'id' => $current->id,
                'name' => $current->name
            ];

            if (! is_null($current->parent_id)) {
                self::getParentTreeArea($current->parent_id, $data);
            }
        }

        return optional((object) $data);
    }
}
