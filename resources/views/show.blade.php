@extends('admin-dashboard::layout')

@section('content')
<h1 class="page-header">
    <a href="/{{ config('admin-support.route') }}">@lang('admin-support::admin.list-title')</a>
</h1>
<div class="sm:rounded-lg bg-white shadow mb-10">
    <div class="py-5 px-4 border-b border-gray-200">
        <svg class="mr-4 text-blue-500 fill-current inline-block w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M6 14H4a2 2 0 0 1-2-2V4c0-1.1.9-2 2-2h12a2 2 0 0 1 2 2v2h2a2 2 0 0 1 2 2v13a1 1 0 0 1-1.7.7L16.58 18H8a2 2 0 0 1-2-2v-2zm0-2V8c0-1.1.9-2 2-2h8V4H4v8h2zm14-4H8v8h9a1 1 0 0 1 .7.3l2.3 2.29V8z"/></svg>
        <a class="text-xl leading-6 font-medium text-gray-900" href="/users/{{ $user->id }}">
            {{ $user->name }}
            <span class="text-base">({{ $user->email }})</span>
        </a>
    </div>

    @if($messages->hasMorePages())
    <a class="block border-b border-gray-200 bg-gray-100 text-gray-900 text-center py-2 w-full hover:bg-gray-200"
        href="{{ $messages->nextPageUrl() }}">
        ▲ @lang('admin-support::admin.earlier')
    </a>
    @endif

    <div class="px-2 md:px-10 pb-2 border-b border-gray-200">
    @for($index=count($messages)-1 ; $index>=0 ; $index--)
        <div class="support-message
            @if($messages[$index]->type == 'userMessage') user @else admin @endif
            @if($messages[$index]->type == 'notification') notification @endif">
            <div class="user-info">
                @if($messages[$index]->type == 'userMessage') {{ $user->name }} @endif
                {{ $messages[$index]->created_at->addHours(3)->format('d.m.Y H:i') }}
                @lang('admin-support::admin.moscow-time')
                @if($messages[$index]->type == 'supportMessage' && $messages[$index]->read_at !== null)
                    <span class="text-green-400">✔</span>
                @endif
            </div>
            <div class="message">
                {!! $messages[$index]->message !!}
                @if($messages[$index]->type == 'supportMessage')
                    <div class="controls">
                        <a href="/{{ config('admin-support.route') }}/message/{{ $messages[$index]->id }}">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" class="inline-block fill-current text-gray-500"><path class="heroicon-ui" d="M6.3 12.3l10-10a1 1 0 0 1 1.4 0l4 4a1 1 0 0 1 0 1.4l-10 10a1 1 0 0 1-.7.3H7a1 1 0 0 1-1-1v-4a1 1 0 0 1 .3-.7zM8 16h2.59l9-9L17 4.41l-9 9V16zm10-2a1 1 0 0 1 2 0v6a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V6c0-1.1.9-2 2-2h6a1 1 0 0 1 0 2H4v14h14v-6z"/></svg>
                        </a>
                        <a href="/{{ config('admin-support.route') }}/message/{{ $messages[$index]->id }}/delete"
                            onclick="return confirm('@lang('admin-support::admin.confirm-delete')')">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="20" height="20" class="inline-block fill-current text-gray-500"><path class="heroicon-ui" d="M8 6V4c0-1.1.9-2 2-2h4a2 2 0 0 1 2 2v2h5a1 1 0 0 1 0 2h-1v12a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V8H3a1 1 0 1 1 0-2h5zM6 8v12h12V8H6zm8-2V4h-4v2h4zm-4 4a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0v-6a1 1 0 0 1 1-1zm4 0a1 1 0 0 1 1 1v6a1 1 0 0 1-2 0v-6a1 1 0 0 1 1-1z"/></svg>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endfor
        <div class="text-right my-4">
            @if($opened)
                <a class="small light button"
                    href="/{{ config('admin-support.route') }}/dialog/{{ $user->id }}/close">@lang('admin-support::admin.close')</a>
                @if(session()->has('return-url'))
                    <a class="small light button"
                        href="/{{ config('admin-support.route') }}/dialog/{{ $user->id }}/close/return">@lang('admin-support::admin.close-and-return')</a>
                @endif
            @else
                @if(session()->has('return-url'))
                    <a class="small light button"
                        href="/{{ config('admin-support.route') }}/dialog/{{ $user->id }}/close/return">@lang('admin-support::admin.return')</a>
                @endif
            @endif
        </div>
    </div>

    @if(! $messages->onFirstPage())
    <a class="block border-b border-gray-200 bg-gray-100 text-gray-900 text-center py-2 w-full hover:bg-gray-200"
        href="{{ $messages->previousPageUrl() }}">
        @lang('admin-support::admin.later') ▼
    </a>
    @endif

	<form action="/{{ config('admin-support.route') }}/dialog/{{ $user->id }}#read" method="POST" class="relative rounded-b-lg">
		@csrf
        <div class="absolute top-0 right-0 mt-2 mr-2 z-10">
            <button class="button circle">
                <svg class="fill-current w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M13 5.41V21a1 1 0 0 1-2 0V5.41l-5.3 5.3a1 1 0 1 1-1.4-1.42l7-7a1 1 0 0 1 1.4 0l7 7a1 1 0 1 1-1.4 1.42L13 5.4z"/></svg>
            </button>
        </div>
        <textarea class="w-full rounded-b-lg px-4 py-4 focus:outline-none
            @error('message') border border-red-500 @enderror"
            name="message" rows="3" id="read" placeholder="@lang('admin-support::admin.message-placeholder')">{{ old('message') }}</textarea>
	</form>
</div>

@endsection


