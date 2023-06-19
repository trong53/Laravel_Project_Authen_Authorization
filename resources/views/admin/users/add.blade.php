@extends('layouts.admin')

@section('title', 'Admin - Add an User')

@section('content')
    <!-- Page Heading -->

    <h1 class="h3 mb-4 text-gray-800">Add an user</h1>

    @if ($errors->any())
        <div class="alert alert-warning"> The data is not correct. Please check your data.</div>
    @endif

    <form action="{{route('admin.users.add')}}" method="post" class="w-100">
        
        <div class="form-group">
            <label for="name">Name : </label>
            <input type="text" id="name" name="name" class="form-control" required value="{{old('name')}}" placeholder="Your name ...">
            @error('name')
            <small class="form-text text-danger">{{$message}}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="email">Email : </label>
            <input type="email" id="email" name="email" class="form-control" required value="{{old('email')}}" placeholder="Your email ...">
            @error('email')
            <small class="form-text text-danger">{{$message}}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="password">Password : </label>
            <input type="password" id="password" name="password" class="form-control" required placeholder="Your password ...">
            @error('password')
            <small class="form-text text-danger">{{$message}}</small>
            @enderror
        </div>

        <div class="form-group">
            <label for="group">Group : </label>
            <select class="form-select ml-3" id="group" name="group_id" aria-label="group of user">
                <option value="0">Select user's group</option>

                @if ($groups->count() > 0)
                    @foreach ($groups as $group)
                        <option value="{{$group->id}}" {{old('group_id')==$group->id ? 'selected' : false}}>{{$group->name}}</option>
                    @endforeach
                @endif

            </select>
            @error('group_id')
            <small class="form-text text-danger">{{$message}}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary px-3">Add user</button>
        <button type="reset" class="btn btn-primary px-3 ml-5">Reset</button>

        @csrf

    </form>
    
@endsection