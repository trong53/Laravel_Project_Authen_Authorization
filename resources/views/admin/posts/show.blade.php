@extends('layouts.admin')

@section('title', 'Show a Post')

@section('content')
    <!-- Page Heading -->
    <h1 class="h3 mb-0 text-primary font-weight-bold text-center">{{$post->title}}</h1>

    <div class="p-3 mt-4 mx-5 bg-white">

        {!! str_replace(['<script>', '</script>'], '(script)', $post->content) !!}
        
    </div>

@endsection