<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{
    Menu,
    Role
};
use App\Libraries\Auth;

class MenuController extends Controller
{
    /**
     * Class constructor
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $userId = $user->id;
        
        $roles = Role::whereHas('users', function ($query) use ($userId) {
            $query->where('id', $userId);
        })
        ->pluck('permissions')
        ->first();

        if (empty($roles)) {
            return $this->response([]);
        }

        $menus = Menu::select('id', 'name', 'classification', 'icon', 'url', 'actions', 'order_classification', 'order_inner_classification', 'is_collapse', 'parent_id')
                ->whereNull('parent_id')
                ->orderByRaw('order_classification asc, order_inner_classification asc')
                ->get();

        $childMenus = Menu::select('id', 'name', 'classification', 'icon', 'url', 'actions', 'is_collapse', 'parent_id', 'order_classification', 'order_inner_classification')
                ->whereNotNull('parent_id')
                ->orderBy('order_classification')
                ->orderBy('order_inner_classification')
                ->get()
                ->groupBy('parent_id');

        $result = $menus->groupBy('classification')->transform(function ($items) use ($roles, $childMenus) {
            $children = $items->filter(function ($item) use ($roles) {
                if (is_null($item->actions)) {
                    $checkchild = Menu::where('parent_id', $item->id)->get();
                    foreach ($checkchild as $itemChild) {
                        return !empty(array_intersect($itemChild->actions, $roles));
                    }
                    return true;
                } elseif (is_array($item->actions)) {
                    return !empty(array_intersect($item->actions, $roles));
                }
            })->transform(function ($item) use ($childMenus, $roles) {
                $childs = [];
                $child = $childMenus->get($item->id);
                if ($child) {
                    foreach ($child as $key => $value) {
                        if (array_intersect($value->actions, $roles)) {
                            $childs[] = [
                                'id' => $value->id,
                                'title' => $value->name,
                                'type' => 'item',
                                'translate' => $value->name,
                                'icon' => $value->icon,
                                'url' => $value->url,
                                'children' => []
                            ];
                        }
                    }
                }

                return [
                    'id'        => $item->id,
                    'title'     => $item->name,
                    'translate' => $item->name,
                    'type'      => $item->is_collapse == 1 ? 'collapse' : 'item',
                    'icon'      => $item->icon,
                    'url'       => $item->url,
                    'children'  => $childs
                ];
            })->values();

            return (object) [
                'id'     => $items[0]->classification,
                'title'     => $items[0]->classification,
                'translate' => $items[0]->classification,
                'type'      => 'group',
                'children'  => $children
            ];
        })->filter(function ($item) {
            return $item->children->isNotEmpty();
        })->values();

        return $this->response($result);
    }
}
