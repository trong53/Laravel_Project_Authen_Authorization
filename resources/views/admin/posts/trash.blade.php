@extends('layouts.admin')

@section('title', 'Posts\'s Trash')

@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-0 text-gray-800 text-center">Listing of trashed Posts</h1>

    @if (session('add-error'))
        <div class="alert alert-danger">{{session('add-error')}}</div>
    @endif

    @if (session('message'))
        <div class="alert alert-success">{{session('message')}}</div>
    @endif

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
                        <a href="" class="title_asc"><i class="fa-solid fa-arrow-up"></i></a>
                        Title 
                        <a href="" class="title_desc"><i class="fa-solid fa-arrow-down"></i></a> 
                    </div> 
                </th>
                <th>
                    <div class="text-center">         
                        Author
                    </div>
                </th>                
                <th class="text-center">Creation date</th>
                <th class="text-center" width="8%"> Restore </th>
                <th class="text-center" width="8%"> Destroy </th>
            </tr>
        </thead>
        <tbody>
            @if ($postList->count() > 0)
                @foreach ($postList as $key=>$post)
        
                        <tr>
                            <td class="text-center"> {{$key + 1}} </td>
                            <td> 
                                <a href="{{route('admin.posts.show', $post)}}">{{ucfirst($post->title)}}</a> 
                            </td>
                            <td> {{$post->user->name}} </td>
                            <td width="15%"> {{$post->created_at}} </td>
                            <td>
                                @can ('restore', $post)
                                <a href="{{route('admin.posts.restore', $post)}}" class="btn btn-warning mx-auto"
                                        onclick="return confirm('Are you sure to restore this post ?')"
                                > Restore
                                </a>
                                @else
                                    <button disabled="disabled" class="btn btn-secondary">Disable</button>                            
                                @endcan
                            </td>                        
                            <td>
                                @can ('forceDelete', $post)
                                <form action="{{route('admin.posts.destroy', $post)}}" method="POST"
                                        onsubmit="return confirm ('Are you sure to delete this post definitely ?')"
                                >
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Destroy</button>
                                </form>
                                @else
                                    <button disabled="disabled" class="btn btn-secondary">Disable</button>                            
                                @endcan
                            </td>
                        </tr>
                                     
                @endforeach
            @else
                <tr>
                    <td colspan="6"> <h3 class="text-center my-5"> There is no post </h3> </td>
                </tr>
            @endif
            
            
        </tbody>
    </table>
    {{ $postList->onEachSide(1)->withQueryString()->links('vendor.pagination.bootstrap-5') }}
@endsection

@section('script')
    <script type="text/javascript">
        // Filter by title - asc
        document.querySelector('.title_asc').onclick = function(e) {
            e.preventDefault();
            let url = window.location.href;
            // console.log(url)
            if (!url.includes('?')) {
                window.location.href = url + '?sort_by=title&sort_type=asc';
            } else {
                if (!url.includes('sort_by=title&sort_type=asc')) {
                    if (!url.includes('sort_by=title&sort_type=desc')) {
                        window.location.href = url + '&sort_by=title&sort_type=asc';
                    } else {
                        window.location.href = url.replace('sort_by=title&sort_type=desc', 'sort_by=title&sort_type=asc');
                    }
                }
            }
        }

        // Filter by title - desc
        document.querySelector('.title_desc').onclick = function(e) {
            e.preventDefault();
            let url = window.location.href;
            // console.log(url)
            if (!url.includes('?')) {
                window.location.href = url + '?sort_by=title&sort_type=desc';
            } else {
                if (!url.includes('sort_by=title&sort_type=desc')) {
                    if (!url.includes('sort_by=title&sort_type=asc')) {
                        window.location.href = url + '&sort_by=title&sort_type=desc';
                    } else {
                        window.location.href = url.replace('sort_by=title&sort_type=asc', 'sort_by=title&sort_type=desc');
                    }
                }
            }
        }

    </script>


@endsection