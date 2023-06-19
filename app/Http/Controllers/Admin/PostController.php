<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Post;
use App\Models\{User, Group};
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostController extends Controller
{
    public function index (Request $request) 
    {   
        // get request data
        $sort_by = $request->sort_by ?? null;
        $sort_type = $request->sort_type ?? null;
        $keyword = $request->keyword ?? null;

        // add filters (keyword for search, order by name et email)
        $postList = $this->indexFilters(new Post(), $sort_by, $sort_type, $keyword);

        // pagination with per_page = 5
        // posts's user must not be trashed - user must have null 'deleted_at'
        $postList = $postList->whereHas('user', function($query){
                                            $query->whereNull('deleted_at');
                                            if (Auth::user()->group->name != 'Administration') {
                                                $query->where('id', Auth::user()->id);  // display only authenticated user's posts
                                            }
                                        })
                            ->paginate(5);

        return view('admin.posts.list', compact('postList'));
    }

    // handle filters for index function
    public function indexFilters ($postList, string $sort_by = null, string $sort_type = null, string $keyword = null)
    {   
        if (!empty($sort_by) && !empty($sort_type)) {
            $postList = $postList->orderBy($sort_by, $sort_type);

        } else {
            $postList = $postList->orderBy('created_at', 'DESC');       // default sort
        }

        if (!empty($keyword)) {
            $postList = $postList->where(function($query) use($keyword) {
                $query->orWhere('title', 'like', "%$keyword%");         // search in 'title' column
                $query->orWhere('content', 'like', "%$keyword%");
                $query->orWhereHas('user', function($query) use($keyword){      // search in 'name' column of 'user' table
                    $query->where('name', 'like', "%$keyword%");
                });
            });
        }

        return $postList;
    }

    public function show (Post $post)
    {
        return view('admin.posts.show', compact('post'));
    }

    public function add () 
    {
        return view('admin.posts.add');
    }

    public function postAdd (Request $request) 
    {   
        // validation of request data
        $request->validate([
            'title'     => ['required', 'unique:posts,title'],
            'content'   => ['required']
        ]);
        
        $status = Post::create([
            'title'     => $request->title,
            'content'   => $request->content,
            'user_id'   => Auth::user()->id
        ]);

        if (empty($status->title)) {
            return redirect(route('admin.posts.index'))->with('add-error', 'You can not add a post at this moment. Please try again later.');
        }

        return redirect(route('admin.posts.index'))->with('message', 'The post has been successfully added.');
    }

    public function edit (Post $post) 
    {
        session(['post' => $post]);

        return view('admin.posts.edit', compact('post'));
    }

    public function postEdit(Request $request) 
    {
        $post = session('post');
        // validation of request data
        $request->validate([
            'title'     => ['required', 'unique:posts,title,'.$post->id],
            'content'   => ['required']
        ]);

        // update user in database. We can use save() for update an user. It's more popular than created() and updated()
        if (!empty($post)) {
            $post->title = $request->title;         // set the attributes
            $post->content = $request->content;
            $status = $post->save();                // return boolean
    
            if ($status) {
                return redirect(route('admin.posts.index'))->with('message', 'The post has been successfully edited.');
            }
        }
        
        return redirect(route('admin.posts.index'))->with('add-error', 'You can not edit this post at this moment. Please try again later.');
    }

    public function delete (Post $post)
    {     
        if (!$post->delete()) {
                return back()->with('add-error', 'You can not delete this post at this moment. Please try again later.');
            }
    
        return back()->with('message', 'The post has been successfully deleted.');
    }

    // Section for Trash ***********************************************************************************************

    public function trash (Request $request) 
    {
        // get request data
        $sort_by = $request->sort_by ?? null;
        $sort_type = $request->sort_type ?? null;
        $keyword = $request->keyword ?? null;

        // add filters (keyword for search, order by name et email)
        $postList = $this->indexFilters(new Post(), $sort_by, $sort_type, $keyword);

        // pagination with per_page = 5
        // posts's user must not be trashed - user must have null 'deleted_at'
        // and posts were trashed
        $postList = $postList->whereHas('user', function($query){
                                                    $query->whereNull('deleted_at');
                                                    if (Auth::user()->group->name != 'Administration') {
                                                        $query->where('id', Auth::user()->id);  // display only authenticated user's posts
                                                    }
                                                })
                            ->onlyTrashed()
                            ->paginate(5);
        
        return view('admin.posts.trash', compact('postList'));
    }

    public function postsOfTrashedUsers (Request $request)
    {
        // DB::enableQueryLog();

        // get request data
        $sort_by = $request->sort_by ?? null;
        $sort_type = $request->sort_type ?? null;
        $keyword = $request->keyword ?? null;

        // add filters (keyword for search, order by name et email)
        $postList = $this->indexFiltersForTrashedUsers(new Post(), $sort_by, $sort_type, $keyword);

        // pagination with per_page = 5
        // posts's user must be trashed
        $postList = $postList->whereDoesntHave('user', function($query){$query->whereNull('deleted_at');})->paginate(5);
        // dd(DB::getQueryLog());
        
        return view('admin.posts.postsOfTrashedUsers', compact('postList'));
    }
    
    public function indexFiltersForTrashedUsers ($postList, string $sort_by = null, string $sort_type = null, string $keyword = null)
    {   
        if (!empty($sort_by) && !empty($sort_type)) {
            $postList = $postList->orderBy($sort_by, $sort_type);

        } else {
            $postList = $postList->orderBy('created_at', 'DESC');       // default sort
        }

        if (!empty($keyword)) {
            $postList = $postList->where(function($query) use($keyword) {
                $query->orWhere('title', 'like', "%$keyword%");         // search in 'title' column
                $query->orWhere('content', 'like', "%$keyword%");       // search in 'content' column
                $query->orWhereHas('trashedUser', function($query) use($keyword){      // search in 'name' column of 'trasheduser' defined in Post Model
                    $query->where('name', 'like', "%$keyword%");
                });
            });
        }

        return $postList;
    }


    public function restore (Post $post)
    {
        if ($post->trashed()) {
            // dd($post->restore());       // return boolean
            if ($post->restore()) {
                return back()->with('message', 'The post has been successfully restored.');
            }

            return back()->with('add-error', 'You can not restore this post at this moment. Please try again later.');
        }

        return back()->with('add-error', 'The restoration of this post is not authorized.');
    }

    public function destroy (Post $post)
    {     
        if ($post->trashed()) {
           
            if ($post->forceDelete()) {
                return back()->with('message', 'The post has been definitely deleted.');
            }

            return back()->with('add-error', 'You can not destroy this post at this moment. Please try again later.');
        }

        return back()->with('add-error', 'The destruction of this post is not authorized.');
    }
}
