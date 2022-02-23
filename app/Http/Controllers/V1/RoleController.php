<?php

namespace App\Http\Controllers\V1;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Libraries\Auth;
use App\Models\Role;
use App\Models\Menu;

class RoleController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('permission:app.role-access.lihat')->only(['index', 'show', 'list']);
        $this->middleware('permission:app.role-access.ubah')->only(['update']);
        $this->middleware('permission:app.role-access.buat')->only(['store', 'getAll']);
        $this->middleware('permission:app.role-access.hapus')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Role::search($request)
            ->orderBy('id', 'asc')
            ->sort($request)
            ->paginate()
            ->appends($request->query());

        return $this->response($query);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = [
            'required',
            'string',
            'min:3',
            'max:255',
            Rule::unique((new Role())->getConnectionName() . '.' . (new Role())->getTable(), 'name'),
        ];

        $this->validate($request, [
            'name' => $rules,
        ], [
            'name.unique' => 'Nama role sudah digunakan.'
        ]);

        $data = $request->only(['name', 'permissions']);

        $data = \DB::transaction(function () use ($data) {
            return Role::create($data);
        });

        return $this->response(true);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();

        $role = Role::where('id', $id)
                ->first();

        if (!$role) {
            return response()->json(['status' => false]);
        }

        $role->menus = Menu::whereNotNull('actions')
            ->where('is_collapse', 0)
            ->whereNull('parent_id')
            ->orderBy('order_classification')
            ->orderBy('order_inner_classification')
            ->get()
            ->toArray();

        $role_arr = $role->toArray();
        $result = [];
        $data = [];
        $arr_parent = [];
        $arr_classification = [];
        $index = null;
        $index_classification_menu = [];

        //listing menu
        $menus = [];
        foreach ($role_arr['menus'] as $key_menu => $val_menu) {
            $menus[$key_menu] = strtolower($val_menu['name']);
                
            $index_action = 0;
            foreach ($val_menu['actions'] as $key_action => $val_action) {
                $data_actions = explode('.', $val_action);
                $action = strtolower(trim($data_actions[1]));

                $data[$val_menu['name']][$index_action] = [
                    "id" => $val_action,
                    "name" => isset($data_actions[3]) ? str_replace('_', ' ', $data_actions[3]) : $action,
                    "value" => $val_action,
                    "status" => false,
                    "submenu" => isset($data_actions[3]) && $action == 'submenu' ? true : false
                ];

                //cek parent
                if (count($arr_parent) > 0) {
                    if (in_array(strtolower($val_menu['name']), $arr_parent)) {
                        $index_menu = array_search(strtolower($val_menu['name']), $arr_parent);
                        $has_index = true;
                    } else {
                        $arr_parent[] = strtolower($val_menu['name']);
                    }
                } else {
                    $arr_parent[] = strtolower($val_menu['name']);
                }

                if (!is_null($role_arr['permissions'])) {
                    if (in_array($val_action, $role_arr['permissions'])) {
                        $data[$val_menu['name']][$index_action]['status'] = true;
                    }
                }
                
                $index_action++;
            }

            if (count($arr_classification) > 0) {
                if (in_array(strtolower($val_menu['classification']), $arr_classification)) {
                    $index = array_search(strtolower($val_menu['classification']), $arr_classification);
                    $index_classification_menu[strtolower($val_menu['classification'])][] = strtolower($val_menu['name']);
                    $index_menu = array_search(strtolower($val_menu['name']), $index_classification_menu[strtolower($val_menu['classification'])]);
                    
                    $result[$index]['role'][$index_menu]['title'] = strtolower($val_menu['name']);
                    $result[$index]['role'][$index_menu]['permissions'] = $data[$val_menu['name']];
                } else {
                    $arr_classification[] = strtolower($val_menu['classification']);
                    $index = array_search(strtolower($val_menu['classification']), $arr_classification);
                    $index_classification_menu[strtolower($val_menu['classification'])][] = strtolower($val_menu['name']);
                    
                    $index_menu = array_search(strtolower($val_menu['name']), $index_classification_menu[strtolower($val_menu['classification'])]);
                    $result[$index]['title'] = $val_menu['classification'];
                    $result[$index]['role'][$index_menu]['title'] = strtolower($val_menu['name']);
                    $result[$index]['role'][$index_menu]['permissions'] = $data[$val_menu['name']];
                }
            } else {
                $index_classification_menu[strtolower($val_menu['classification'])][] = strtolower($val_menu['name']);
                $arr_classification[] = strtolower($val_menu['classification']);
                $result[count($arr_classification) - 1] = [
                    'title' => $val_menu['classification']
                ];
                if (isset($result[count($arr_classification) - 1]['role'])) {
                    $count_menu_classification = count($result[count($arr_classification) - 1]['role']) - 1;
                } else {
                    $count_menu_classification = 0;
                }

                $result[count($arr_classification) - 1]['role'][$count_menu_classification] = [
                    'title' => strtolower($val_menu['name']),
                    'permissions' => $data[$val_menu['name']]
                ];
            }
        }

        $finalResult = ['name' => $role->name, 'role' => $result];

        return $this->response($finalResult);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = Auth::user();

        $userType = $user->type;

        $this->validate($request, [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                Rule::unique((new Role())->getConnectionName() . '.' . (new Role())->getTable(), 'name')
                    ->ignore($id, 'id'),
            ],
        ], [
            'name.unique' => 'Nama role sudah digunakan.'
        ]);

        $model = Role::whereNotIn('id', [1, 2, 3])
            ->findOrFail($id);
        
        $data = $request->only(['name', 'permissions']);
        $status = \DB::transaction(function () use ($model, $data) {
            return $model->update($data);
        });

        return $this->response(($status) ? true : false);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $model = Role::whereNotIn('id', [1, 2, 3])->findOrFail($id);
        $status = $model->delete();

        return $this->response(($status) ? true : false);
    }

    /**
     * Display list role for select2
     */
    public function list()
    {
        $role = Role::select('id', 'name');
        
        $data = $role->get();

        return $this->response($data);
    }

    /**
     * Get all permissions for principal
     */
    public function getAll()
    {
        $role_arr['menus'] = Menu::whereNotNull('actions')
            ->where('is_collapse', 0)
            ->whereNull('parent_id')
            ->orderBy('order_classification')
            ->orderBy('order_inner_classification')
            ->get()
            ->toArray();

        $result = [];
        $data = [];
        $i = 0;
        $arr_parent = [];
        $arr_classification = [];
        $count_parent = count($arr_parent);
        $index = null;
        $has_index = false;
        $index_classification_menu = [];

        //listing menu
        $menus = [];
        foreach ($role_arr['menus'] as $key_menu => $val_menu) {
            $menus[$key_menu] = strtolower($val_menu['name']);
                
            $index_action = 0;
            foreach ($val_menu['actions'] as $key_action => $val_action) {
                $data_actions = explode('.', $val_action);
                $action = strtolower(trim($data_actions[1]));

                $data[$val_menu['name']][$index_action] = [
                    "id" => $val_action,
                    "name" => isset($data_actions[3]) ? str_replace('_', ' ', $data_actions[3]) : $action,
                    "value" => $val_action,
                    "status" => false,
                    "submenu" => isset($data_actions[3]) && $action == 'submenu' ? true : false
                ];

                //cek parent
                if (count($arr_parent) > 0) {
                    if (in_array(strtolower($val_menu['name']), $arr_parent)) {
                        $index_menu = array_search(strtolower($val_menu['name']), $arr_parent);
                        $has_index = true;
                    } else {
                        $arr_parent[] = strtolower($val_menu['name']);
                    }
                } else {
                    $arr_parent[] = strtolower($val_menu['name']);
                }

                // if (in_array($val_action, $role_arr['permissions'])) {
                //     $data[$index_action]['status'] = true;
                // }
                $index_action++;
            }

            if (count($arr_classification) > 0) {
                if (in_array(strtolower($val_menu['classification']), $arr_classification)) {
                    $index = array_search(strtolower($val_menu['classification']), $arr_classification);
                    $index_classification_menu[strtolower($val_menu['classification'])][] = strtolower($val_menu['name']);
                    $index_menu = array_search(strtolower($val_menu['name']), $index_classification_menu[strtolower($val_menu['classification'])]);
                    
                    $result[$index]['role'][$index_menu]['title'] = strtolower($val_menu['name']);
                    $result[$index]['role'][$index_menu]['permissions'] = $data[$val_menu['name']];
                } else {
                    $arr_classification[] = strtolower($val_menu['classification']);
                    $index = array_search(strtolower($val_menu['classification']), $arr_classification);
                    $index_classification_menu[strtolower($val_menu['classification'])][] = strtolower($val_menu['name']);
                    
                    $index_menu = array_search(strtolower($val_menu['name']), $index_classification_menu[strtolower($val_menu['classification'])]);
                    $result[$index]['title'] = $val_menu['classification'];
                    $result[$index]['role'][$index_menu]['title'] = strtolower($val_menu['name']);
                    $result[$index]['role'][$index_menu]['permissions'] = $data[$val_menu['name']];
                }
            } else {
                $index_classification_menu[strtolower($val_menu['classification'])][] = strtolower($val_menu['name']);
                $arr_classification[] = strtolower($val_menu['classification']);
                $result[count($arr_classification) - 1] = [
                    'title' => $val_menu['classification']
                ];
                if (isset($result[count($arr_classification) - 1]['role'])) {
                    $count_menu_classification = count($result[count($arr_classification) - 1]['role']) - 1;
                } else {
                    $count_menu_classification = 0;
                }

                $result[count($arr_classification) - 1]['role'][$count_menu_classification] = [
                    'title' => strtolower($val_menu['name']),
                    'permissions' => $data[$val_menu['name']]
                ];
            }
        }

        return $this->response($result);
    }
}
