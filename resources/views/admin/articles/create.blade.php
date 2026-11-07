@extends('layouts.admin')

@section('title', 'وتاری نوێ | پەیمانگەی لۆزان')

@section('content')
    <div>
        <h1 class="text-2xl font-extrabold tracking-tight">وتاری نوێ</h1>
        <p class="mt-1 text-sm text-stone-500 dark:text-stone-400">وتارێکی نوێ بنووسە و بڵاوی بکەرەوە یان وەک ڕەشنووس هەڵیبگرە.</p>
    </div>

    @include('admin.articles._form')
@endsection
