<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\{Group, Module};

use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index(Request $request) 
    {   
        // get request data
        $sort_by = $request->sort_by ?? [];
        $sort_type = $request->sort_type ?? [];
        $keyword = $request->keyword ?? null;

        // add filters (keyword for search, order by name)
        $groupList = $this->indexFilters(new Group(), $sort_by, $sort_type, $keyword);

        //pagination with per_page = 5
        $groupList = $groupList->paginate(5);

        return view('admin.groups.list', compact('groupList'));
    }

    // handle filters for index function
    public function indexFilters($groupList, $sort_by = [], $sort_type = [], $keyword = null)
    {   
        if (!empty($sort_by) && !empty($sort_type)) {
            foreach ($sort_by as $key => $item) {
                if (in_array($item, ['name'])) {
                    $groupList = $groupList->orderBy($item, $sort_type[$key]);
                }
            }
        } else {
            $groupList = $groupList->orderBy('created_at', 'DESC');   // default sort
        }

        if (!empty($keyword)) {
            $groupList = $groupList->where('name', 'like', "%$keyword%");      // search in 'name' column
        }

        return $groupList;
    }

    public function add() 
    {
        return view('admin.groups.add');
    }
    
    public function postAdd(Request $request) 
    {   
        // validation of request data
        $request->validate([
            'name'      => ['required', 'min:2', 'unique:groups,name']
        ]);
        
        $status = Group::create([
            'name'      => $request->name,
            'user_id'   => Auth::user()->id
        ]);

        if (empty($status->name)) {
            return redirect(route('admin.groups.index'))->with('add-error', 'You can not add a group at this moment. Please try again later.');
        }

        return redirect(route('admin.groups.index'))->with('message', 'The group has been successfully added.');
    }

    public function edit(Group $group) 
    {
        session(['group' => $group]);
        return view('admin.groups.edit', compact('group'));
    }

    public function postEdit(Request $request) 
    {
        $group = session('group');            // get user instance

        // validation of request data
        $request->validate([
            'name'      => ['required', 'min:2', 'unique:groups,name,'.$group->id]
        ]);

        // update user in database. We can use save() for update an user. It's more popular than created() and updated()
        $group->name = $request->name;       // set the attributes
        $status = $group->save();            // return boolean

        if (!$status) {
            return redirect(route('admin.groups.index'))->with('add-error', 'You can not edit this group at this moment. Please try again later.');
        }

        return redirect(route('admin.groups.index'))->with('message', 'The group has been successfully edited.');
    }

    public function delete(Group $group)
    {
        if (Auth::user()->group_id != $group->id) {        // can not delete the authenticated user'group (himself)
            
            $users_count = $group->users->count();

            if ($users_count == 0) {
                if (!$group->delete()) {
                    return back()->with('add-error', 'You can not delete this group at this moment. Please try again later.');
                } else {
                    return back()->with('message', 'The group has been successfully deleted.');
                }
            } else {
                return back()->with('add-error', 'This group contains '.$users_count.' users. You can not delete it.');
            }
        }
        return back()->with('add-error', 'You can not delete your group.');
    }

    /************************* Permission section *********************************************/

    public function permission(Group $group)
    {   
        // put $group instance into session
        session(['group'=>$group]);

        // get permissions in groups table
        $permissions = $group->permissions;
        if (!empty($permissions)) {
            $permissionsArray = json_decode($permissions, true);
        } else {
            $permissionsArray = [];
        }

        // get all modules
        $modules = Module::all();

        // define permission name
        $roleArr = ['read', 'create', 'update', 'delete'];

        return view('admin.groups.permission', compact('group', 'modules', 'roleArr', 'permissionsArray'));
    }

    public function postPermission(Request $request)
    {   
        $group = session('group');
        
        if (!empty($request->role)) {
            $roleArr = $request->role;
        } else {
            $roleArr = [];
        }

        $roleJson = json_encode($roleArr);
        if (!empty($group)) {
            $group->permissions = $roleJson;
        
            if ($group->save()) {
                return back()->with('message', 'The permission has been done.');
            }
        }
        
        return back()->with('error-message', 'You can not set up the permission at this moment. Please try again later.');  
    }
}













/*
$request->role :

array:3 [▼ // app\Http\Controllers\Admin\GroupController.php:128
  "users" => array:2 [▶
    0 => "Update"
    1 => "Delete"
  ]
  "groups" => array:3 [▶
    0 => "Read"
    1 => "Create"
    2 => "Permission"
  ]
  "posts" => array:2 [▶
    0 => "Read"
    1 => "Update"
  ]
]
*/