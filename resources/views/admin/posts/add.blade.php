@extends('layouts.admin')

@section('title', 'Admin - Add a post')

@section('content')
    <!-- Page Heading -->

    <h1 class="h3 mb-4 text-gray-800">Add a post</h1>

    @if ($errors->any())
        <div class="alert alert-warning"> The data is not correct. Please check your data.</div>
    @endif

    <form action="{{route('admin.posts.add')}}" method="POST" class="w-100">
        
        <div class="form-group">
            <label for="title">Title : </label>
            <input type="text" id="title" name="title" class="form-control" required value="{{old('title')}}" placeholder="Title ...">
            
            @error('title')
            <small class="form-text text-danger">{{$message}}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="content">Content : </label>
            <textarea  id="content" rows="10" name="content" class="form-control" required placeholder="Content ...">
                {{old('content')}}
            </textarea>
            {{-- <script>CKEDITOR.replace('content')</script> --}}
            
            @error('content')
            <small class="form-text text-danger">{{$message}}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary px-3">Add post</button>
        <button type="reset" class="btn btn-primary px-3 ml-5">Reset</button>

        @csrf

    </form>
    
@endsection

@section('script')

    {{-- <script type="text/javascript">
        SyntaxHighlighter.all();
        CKEDITOR.replace('content');
    </script> --}}
    
@endsection