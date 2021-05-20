@extends('admin-dashboard::layout')

@section('content')
    <h1 class="page-header mb-6">@lang('admin-support::admin.list-title')</h1>

<div class="text-center flex flex-col sm:flex-row justify-between mx-4">
    <div class="text-center">
        @if(!$list or $list == 'all')
            <span class="selector active">@lang('admin-support::admin.list-all') ({{ $stats['all'] }})</span>
        @else
            <a class="selector" href="/{{ config('admin-support.route') }}">@lang('admin-support::admin.list-all') ({{ $stats['all'] }})</a>
        @endif

        @if($list == 'search')
            <span class="selector active">@lang('admin-support::admin.list-search') ({{ $stats['search'] }})</span>
        @else
            @if($list == 'unread')
                <span class="selector active">@lang('admin-support::admin.list-unread') ({{ $stats['unread'] }})</span>
            @else
                <a class="selector" href="/{{ config('admin-support.route') }}/unread">@lang('admin-support::admin.list-unread') ({{ $stats['unread'] }})</a>
            @endif
        @endif
    </div>
    <div class="sm:ml-2">
        <form action="/{{ config('admin-support.route') }}/search" role="search" style="white-space: nowrap;">
            <input class="py-1 px-3 w-auto border border-r-0 rounded-l-lg focus:outline-none shadow"
                type="text" name="q" value="{{ $query ?? '' }}" placeholder="@lang('admin-support::admin.placeholder')" required><button type="submit" class="py-1 px-3 border rounded-r-lg border-indigo-500 bg-indigo-500 text-white shadow">
                <svg class="fill-current text-white h-5 inline-block" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path class="heroicon-ui" d="M16.32 14.9l5.39 5.4a1 1 0 0 1-1.42 1.4l-5.38-5.38a8 8 0 1 1 1.41-1.41zM10 16a6 6 0 1 0 0-12 6 6 0 0 0 0 12z"/></svg>
            </button>
        </form>
    </div>
</div>

@if(count($dialogs) > 0)
    @for($index=count($dialogs)-1 ; $index>=0 ; $index--)
        @include('admin-support::card', ['dialog' => $dialogs[$index]])
    @endfor
@else
    <div class="p-4 m-4 rounded-lg bg-white text-center text-gray-700 shadow">
        @lang('admin-support::admin.list-empty')
    </div>
@endif

{{ $dialogs->links('admin-dashboard::pagination') }}

@endsection
