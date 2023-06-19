<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\{User, Group};

use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

use App\Rules\{EmailRegex, NameRegex};

use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request) 
    {   
        // get request data
        $sort_by = $request->sort_by ?? [];
        $sort_type = $request->sort_type ?? [];
        $keyword = $request->keyword ?? null;

        // check if a user's group was selected
        if (!empty($request->group_id) && in_array($request->group_id, groupsID())) {
            $selected_group = Group::find($request->group_id);
            $userList = $selected_group->users();

        } else {
            $userList = new User();
        }

        // add filters (keyword for search, order by name et email)
        $userList = $this->indexFilters($userList, $sort_by, $sort_type, $keyword);

        //pagination with per_page = 5
        $userList = $userList->paginate(5);

        // dd($userList->group()); // can not because $userList is a Collection
        // dd($userList[0]->group);  // infos of group as defined in User model

        // get all groups 
        $groups = Group::all();

        return view('admin.users.list', compact('userList', 'groups'));
    }

    // handle filters for index function
    public function indexFilters($userList, $sort_by = [], $sort_type = [], $keyword = null)
    {   
        if (!empty($sort_by) && !empty($sort_type)) {
            foreach ($sort_by as $key => $item) {
                if (in_array($item, ['name', 'email'])) {
                    $userList = $userList->orderBy($item, $sort_type[$key]);
                }
            }
        } else {
            $userList = $userList->orderBy('created_at', 'DESC');   // default sort
        }

        if (!empty($keyword)) {
            $userList = $userList->where(function($query) use($keyword) {
                $query->orWhere('name', 'like', "%$keyword%");      // search in 'name' column
                $query->orWhere('email', 'like', "%$keyword%");     // search in 'email' column
            });
        }
        return $userList;
    }

    public function add() 
    {
        $groups = Group::all();
        return view('admin.users.add', compact('groups'));
    }

    public function postAdd(Request $request) 
    {   
        // validation of request data
        $request->validate([
            'name'      => ['required', 'min:2', new NameRegex],
            'email'     => ['required', 'email', 'unique:users,email', new EmailRegex],
            'password'  => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
            'group_id'  => ['required', 'integer',
                            function($attribute, $value, $fail) {
                                if ($value <= 0 || empty($value))
                                    $fail('You have to select an user\'s group');
                            },
                            function($attribute, $value, $fail) {
                                if (!in_array($value, groupsID()))
                                    $fail('The user\'s group does not exist');
                            }
                            ]
        ]);
        
        // insert user in database. I use create(array) who return this created user's infos.
        // foreign key : table child chi anh huong khi delete hay update tren parent table. Khong co anh huong gi o day.
        $status = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'group_id'  => $request->group_id
        ]);

        if (empty($status->name)) {
            return redirect(route('admin.users.index'))->with('add-error', 'You can not add an user at this moment. Please try again later');
        }

        return redirect(route('admin.users.index'))->with('message', 'The user has been successfully added.');
    }

    public function edit(User $user) 
    {
        // get all groups 
        $groups = Group::all();

        session(['user' => $user]);
        return view('admin.users.edit', compact('groups', 'user'));
    }

    public function postEdit(Request $request) 
    {
        // validation of request data
        $request->validate([
            'name'      => ['required', 'min:2', new NameRegex],
            'email'     => ['required', 'email', 'unique:users,email,'.session('user')->id, new EmailRegex],
            'password'  => ['required', Password::min(8)->letters()->mixedCase()->numbers()->symbols()->uncompromised()],
            'group_id'  => ['required', 'integer',
                            function($attribute, $value, $fail) {
                                if ($value <= 0 || empty($value))
                                    $fail('You have to select an user\'s group');
                            },
                            function($attribute, $value, $fail) {
                                if (!in_array($value, groupsID()))
                                    $fail('The user\'s group does not exist');
                            }
                            ]
        ]);

        // update user in database. We can use save() for update an user. It's more popular than created() and updated()
        $user = session('user');            // get user instance
        $user->name = $request->name;       // set the attributes
        $user->email = $request->email;
        $user->password = $request->password;
        $user->group_id = $request->group_id;
        $status = $user->save();            // return boolean

        if (!$status) {
            return redirect(route('admin.users.index'))->with('add-error', 'You can not edit this user at this moment. Please try again later');
        }

        return redirect(route('admin.users.index'))->with('message', 'The user has been successfully edited.');
    }

    public function delete(User $user)
    {
        // dd($user->delete());     // return boolean
        
        if (Auth::user()->id != $user->id) {        // can no delete the authenticated user (himself)
            if (!$user->delete()) {
                return back()->with('add-error', 'You can not delete this user at this moment. Please try again later');
            }
    
            return back()->with('message', 'The user has been successfully deleted.');
        }
        return back()->with('add-error', 'You can not delete your account.');
    }

    // Section for Trash ***********************************************************************************************

    public function trash(Request $request) 
    {
        // get request data
        $sort_by = $request->sort_by ?? [];
        $sort_type = $request->sort_type ?? [];
        $keyword = $request->keyword ?? null;

        // check if a user's group was selected
        if (!empty($request->group_id) && in_array($request->group_id, groupsID())) {
            $selected_group = Group::find($request->group_id);
            $userList = $selected_group->users();

        } else {
            $userList = new User();
        }

        // add filters (keyword for search, order by name et email)
        $userList = $this->indexFilters($userList, $sort_by, $sort_type, $keyword);

        //pagination with per_page = 5
        $userList = $userList->onlyTrashed()->paginate(5);

        // dd($userList->group()); // can not because $userList is a Collection
        // dd($userList[0]->group);  // infos of group as defined in User model

        // get all groups 
        $groups = Group::all();

        return view('admin.users.trash', compact('userList', 'groups'));
    }

    public function restore(User $user)
    {
        if ($user->trashed()) {
            // dd($user->restore());       // return boolean
            if ($user->restore()) {
                return back()->with('message', 'The user has been successfully restored.');
            }

            return back()->with('add-error', 'You can not restore this user at this moment. Please try again later');
        }
    }

    public function destroy(User $user)
    {
        if (Auth::user()->id != $user->id) {
              
            if ($user->trashed()) {
                // dd($user->restore());       // return boolean
                if ($user->forceDelete()) {
                    return back()->with('message', 'The user has been definitely deleted.');
                }

                return back()->with('add-error', 'You can not destroy this user at this moment. Please try again later');
            }
        }
        return back()->with('add-error', 'You can not delete your account.');
    }
}