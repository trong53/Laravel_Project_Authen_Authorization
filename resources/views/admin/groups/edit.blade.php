@extends('layouts.admin')

@section('title', 'Admin - Edit a group')

@section('content')
    <!-- Page Heading -->

    <h1 class="h3 mb-4 text-gray-800">Edit a group</h1>

    @if ($errors->any())
        <div class="alert alert-warning"> The data is not correct. Please check your data.</div>
    @endif

    <form action="{{route('admin.groups.postEdit')}}" method="POST" class="w-100">
        
        <div class="form-group">
            <label for="name">Name : </label>
            <input type="text" id="name" name="name" class="form-control" required value="{{old('name') ?? $group->name}}" placeholder="Group name ...">
            @error('name')
            <small class="form-text text-danger">{{$message}}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary px-3">Edit group</button>
        <button type="reset" class="btn btn-primary px-3 ml-5">Reset</button>

        @csrf

    </form>
    
@endsection