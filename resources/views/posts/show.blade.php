@extends('layouts.app')

@section('meta')
<meta name="description" content="{{$post->description}}" />
@endsection

@section('content')
<article class="container">
  <header>
    <time datetime="{{$post->publish_at}}">{{$post->publish_at->format('Y.m.d')}}</time>
    <div class="d-flex align-items-center">
      @foreach($post->categories as $category)
      <div class="mr-1">{{$category->name}}</div>
      @endforeach
    </div>
    <h1>{{$post->title}}</h1>
  </header>
  <div class="content">
    @php
      /* ここにコンテンツを表示します。 */
    @endphp
  </div>
  <a class="btn btn-dark" href="{{route('post.index',['slug' => $postType->slug])}}" role="button">一覧ページへ</a>
</article>
@endsection