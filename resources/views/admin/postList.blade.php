@extends('layouts.admin')

@section('title', 'Admin - Posts Listing')

@section('content')
    <h1> Listing of Posts </h1>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th width="5%"> N° </th>
                <th> Title </th>
                <th width="25%"> Author </th>
                <th width="17%"> Created date </th>
                <th width="17%"> Modified date </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($allPosts as $key=>$post)
            <tr>
                <td> {{$key + 1}} </td>
                <td> {{$post->title}} </td>
                <td> {{}} </td>
                <td> {{$post->created_at}} </td>
                <td> {{$post->updated_at}} </td>
            </tr> 
            @endforeach

        </tbody>
    </table>

@endsection