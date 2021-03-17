@extends('layouts.app')

@section('meta')
<meta name="description" content="{{$postType->description}}" />
@endsection

@section('content')
<div class="container">
  <h1>{{$postType->name}}</h1>
  <div class="list-group mb-4">
    @foreach($posts as $post)
    <a class="list-group-item list-group-item-action" href="{{route('post.show',['slug' => $postType->slug,'postSlug' => $post->slug])}}">
      <div class="d-flex align-items-center">
        <small class="mr-3">
          <time datetime="{{$post->publish_at}}">{{$post->publish_at->format('Y.m.d')}}</time>
        </small>
        <h2 class="h5 mb-0">{{$post->title}}</h2>
      </div>
      @if(!empty($post->description))
      <p class="mb-0">{!!nl2br($post->description)!!}</p>
      @endif
    </a>
    @endforeach
  </div>
  {{$posts->links()}}
</div>
@endsection