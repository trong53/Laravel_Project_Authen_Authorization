@extends('layouts.admin')

@section('title', 'Users\'s Trash')

@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-0 text-gray-800 text-center">Listing of trashed Users</h1>

    @if (session('add-error'))
        <div class="alert alert-danger">{{session('add-error')}}</div>
    @endif

    @if (session('message'))
        <div class="alert alert-success">{{session('message')}}</div>
    @endif

    <hr>

    <form action="" method="GET" class="mb-4">
        <div class="row flex-nowrap justify-content-center">
            {{-- Select user's group --}}
            <div class="col-3">
                <select name="group_id" id="group_id" class="form-control">
                    <option value="0">All user's groups</option>

                    @if ($groups->count() > 0)
                        @foreach ($groups as $group)
                            <option value="{{$group->id}}" {{request()->group_id == $group->id ? 'selected' : false}}>{{ucwords($group->name)}}</option>
                        @endforeach
                    @endif

                </select>
            </div>
            {{-- Search --}}
            <div class="col-7">
                <input type="search" name="keyword" id="keyword" class="form-control" placeholder="Search ...">
            </div>

            <div class="col-2">
                <button type="submit" class="btn btn-primary d-flex align-items-center px-4 ml-auto">
                    <i class="small material-icons">search</i>
                    <span class="ml-2">Search</span>
                </button>
            </div>
        </div>
        
    </form>

    <table class="table table-bordered table-hover">
        <thead class="text-primary">
            <tr>
                <th width="5%" class="text-center"> Number </th>
                <th>
                    <div class="d-flex align-items-center justify-content-between">
                        <a href="" class="name_asc"><i class="fa-solid fa-arrow-up"></i></a>
                        Name 
                        <a href="" class="name_desc"><i class="fa-solid fa-arrow-down"></i></a> 
                    </div> 
                </th>
                <th>
                    <div class="d-flex align-items-center justify-content-between">
                        <a href="" class="email_asc"><i class="fa-solid fa-arrow-up"></i></a>
                        Email 
                        <a href="" class="email_desc"><i class="fa-solid fa-arrow-down"></i></a> 
                    </div>
                </th>                
                <th>
                    <div class="text-center">
                        Group  
                    </div>
                </th>
                <th>Creation date</th>
                <th>Deletion date</th>
                <th class="text-center" width="8%"> Restore </th>
                <th class="text-center" width="8%"> Destroy </th>
            </tr>
        </thead>
        <tbody>
            @if ($userList->count() > 0)
                @foreach ($userList as $key=>$user)
                    <tr>
                        <td class="text-center"> {{$key + 1}} </td>
                        <td> {{ucwords($user->name)}} </td>
                        <td> {{$user->email}} </td>
                        <td class="text-center"> {{ucwords($user->group->name)}} </td>
                        <td width="15%"> {{$user->created_at}} </td>
                        <td width="15%"> {{$user->deleted_at}} </td>
                        <td>
                            {{-- @can ('restore', $user) --}}
                            <a href="{{route('admin.users.restore', [$user])}}" class="btn btn-warning mx-auto"
                                    onclick="return confirm('Are you sure to restore this user ?')"
                            > Restore 
                            </a>
                            {{-- @else
                                <button disabled="disabled" class="btn btn-secondary">Disable</button>                            
                            @endcan --}}
                        </td>
                        <td>
                            {{-- @can ('forceDelete', $user) --}}
                                {{-- check if authenticated user -> can not delete  --}}
                                @if (Auth::user()->id != $user->id)
                                    <form action="{{route('admin.users.destroy', $user)}}" method="POST"
                                        onsubmit="return confirm ('Are you sure to delete this user definitely ?')"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Destroy</button>
                                    </form>
                                @else
                                    <button disabled="disabled" class="btn btn-secondary">nothing</button> 
                                @endif
                            {{-- @else
                                <button disabled="disabled" class="btn btn-secondary">Disable</button>                            
                            @endcan --}}
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="8"> <h3 class="text-center my-5"> There is no user </h3> </td>
                </tr>
            @endif
            
            
        </tbody>
    </table>
    {{ $userList->onEachSide(1)->withQueryString()->links('vendor.pagination.bootstrap-5') }}
@endsection

@section('script')
    <script type="text/javascript">
        // Filter by name - asc
        document.querySelector('.name_asc').onclick = function(e) {
            e.preventDefault();
            let url = window.location.href;
            // console.log(url)
            if (!url.includes('?')) {
                window.location.href = url + '?sort_by[]=name&sort_type[]=asc';
            } else {
                if (!url.includes('sort_by[]=name&sort_type[]=asc')) {
                    if (!url.includes('sort_by[]=name&sort_type[]=desc')) {
                        window.location.href = url + '&sort_by[]=name&sort_type[]=asc';
                    } else {
                        window.location.href = url.replace('sort_by[]=name&sort_type[]=desc', 'sort_by[]=name&sort_type[]=asc');
                    }
                }
            }
        }

        // Filter by name - desc
        document.querySelector('.name_desc').onclick = function(e) {
            e.preventDefault();
            let url = window.location.href;
            // console.log(url)
            if (!url.includes('?')) {
                window.location.href = url + '?sort_by[]=name&sort_type[]=desc';
            } else {
                if (!url.includes('sort_by[]=name&sort_type[]=desc')) {
                    if (!url.includes('sort_by[]=name&sort_type[]=asc')) {
                        window.location.href = url + '&sort_by[]=name&sort_type[]=desc';
                    } else {
                        window.location.href = url.replace('sort_by[]=name&sort_type[]=asc', 'sort_by[]=name&sort_type[]=desc');
                    }
                }
            }
        }

        // Filter by email - asc
        document.querySelector('.email_asc').onclick = function(e) {
            e.preventDefault();
            let url = window.location.href;
            // console.log(url)
            if (!url.includes('?')) {
                window.location.href = url + '?sort_by[]=email&sort_type[]=asc';
            } else {
                if (!url.includes('sort_by[]=email&sort_type[]=asc')) {
                    if (!url.includes('sort_by[]=email&sort_type[]=desc')) {
                        window.location.href = url + '&sort_by[]=email&sort_type[]=asc';
                    } else {
                        window.location.href = url.replace('sort_by[]=email&sort_type[]=desc', 'sort_by[]=email&sort_type[]=asc');
                    }
                }
            }
        }

        // Filter by email - desc
        document.querySelector('.email_desc').onclick = function(e) {
            e.preventDefault();
            let url = window.location.href;
            // console.log(url)
            if (!url.includes('?')) {
                window.location.href = url + '?sort_by[]=email&sort_type[]=desc';
            } else {
                if (!url.includes('sort_by[]=email&sort_type[]=desc')) {
                    if (!url.includes('sort_by[]=email&sort_type[]=asc')) {
                        window.location.href = url + '&sort_by[]=email&sort_type[]=desc';
                    } else {
                        window.location.href = url.replace('sort_by[]=email&sort_type[]=asc', 'sort_by[]=email&sort_type[]=desc');
                    }
                }
            }
        }

    </script>

@endsection