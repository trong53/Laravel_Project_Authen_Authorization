@extends('layouts.admin')

@section('title', 'Groups\'s Management')

@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-0 text-gray-800 text-center">Listing of Groups</h1>

    @if (session('add-error'))
        <div class="alert alert-danger">{{session('add-error')}}</div>
    @endif

    @if (session('message'))
        <div class="alert alert-success">{{session('message')}}</div>
    @endif

    @can('create', App\Models\Group::class)
    <p>
        <a href="{{route('admin.groups.add')}}" class="btn btn-primary"> Add a group </a>
    </p>
    @endcan

    <hr>

    <form action="" method="GET" class="mb-4">
        <div class="row flex-nowrap justify-content-center">
            {{-- Search --}}
            <div class="col-10">
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
                <th width="15%">
                    <div class="text-center">
                        Created_by 
                    </div>
                </th>                
                <th width="10%">
                    <div class="text-center">
                        Permissions  
                    </div>
                </th>
                <th width="15%">Creation date</th>
                <th colspan="2" class="text-center" width="16%"> Actions </th>
            </tr>
        </thead>
        <tbody>
            @if ($groupList->count() > 0)
                @foreach ($groupList as $key=>$group)
                    <tr>
                        <td class="text-center"> {{$key + 1}} </td>
                        <td> {{ucwords($group->name)}} </td>
                        <td> {{$group->user->name ?? false}} </td>
                        <td> 
                            <a href="{{route('admin.groups.permission', $group)}}" class="btn btn-primary"> Permission </a> 
                        </td>
                        <td> {{$group->created_at}} </td>
                        <td>
                            <a href="{{route('admin.groups.edit', [$group])}}" class="btn btn-warning mx-auto"> Modify </a>
                        </td>
                        <td>
                            {{-- check if authenticated user -> can not delete  --}}
                            @if (Auth::user()->group_id != $group->id)
                                <form action="{{route('admin.groups.delete', $group)}}" method="POST"
                                        onsubmit="return confirm ('Are you sure to delete this group ?')"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            @endif
                            
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="7"> <h3 class="text-center my-5"> There is no group </h3> </td>
                </tr>
            @endif
            
            
        </tbody>
    </table>
    {{ $groupList->onEachSide(1)->withQueryString()->links('vendor.pagination.bootstrap-5') }}
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

    </script>

@endsection