@extends('layouts.admin')

@section('title', 'Admin - Permission Setup')

@section('content')

    <!-- Page Heading -->
    <h1 class="h3 mb-4 text-gray-800">Permission for group : {{$group->name}}</h1>

    {{-- Display error --}}
    @if (session('error-message'))
        <div class="alert alert-danger">{{session('error-message')}}</div>
    @endif

    @if (session('message'))
        <div class="alert alert-success">{{session('message')}}</div>
    @endif

    <form action="{{route('admin.groups.postPermission')}}" method="POST">

        <table class="table table-bordered table-hover">
            <thead class="text-primary">
                <tr>
                    <th width="25%"> Modules </th>
                    <th> Permissions Management </th>
                </tr>
            </thead>
            <tbody>
                @if ($modules->count() > 0)
                    @foreach ($modules as $module)
                        <tr>
                            <td> {{$module->title}} </td>
                            <td> 
                                <div class="row">
                                    @foreach ($roleArr as $role)
                                        <div class="col-2">
                                            <label for="role_{{$module->name}}_{{$role}}">
                                                <input type="checkbox" name="role[{{$module->name}}][]" id="role_{{$module->name}}_{{$role}}" 
                                                        value="{{$role}}"
                                                        {{ isPermission($permissionsArray, $module->name, $role) ? 'checked' : false }}
                                                >
                                                {{ ucwords($role) }} 
                                            </label>
                                        </div>
                                    @endforeach
                                    
                                    @if ($module->name === 'groups')
                                        <div class="col-2">
                                            <label for="role_{{$module->name}}_permission">
                                                <input type="checkbox" name="role[{{$module->name}}][]" id="role_{{$module->name}}_permission" 
                                                        value="permission"
                                                        {{ isPermission($permissionsArray, $module->name, 'permission') ? 'checked' : false }}
                                                >
                                                Permission 
                                            </label>
                                        </div>
                                    @endif

                                </div>                        
                            </td>
                        </tr>
                    @endforeach                    
                @endif
            </tbody>
        </table>

        @csrf
        <button type="submit" class="btn btn-primary">Validate</button>

    </form>
    
@endsection