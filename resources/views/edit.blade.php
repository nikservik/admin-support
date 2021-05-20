@extends('admin-dashboard::layout')

@section('content')
<h1 class="page-header">
    <a href="/{{ config('admin-support.route') }}/dialog/{{ $user->id }}">@lang('admin-support::admin.list-title')</a>
</h1>

<div class="sm:rounded-lg bg-white shadow mb-10">
    <div class="py-5 px-4 border-b border-gray-200">
        <svg xmlns="http://www.w3.org/2000/svg" class="mr-4 text-blue-500 inline-block h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
        </svg>
        <span class="text-xl leading-6 font-medium text-gray-900">
            @lang('admin-support::admin.edit-title')
            {{ $user->name }}
        </span>
    </div>
</div>

<div class="my-4 mx-2 md:mx-10 pl-10">
	<form action="/{{ config('admin-support.route') }}/message/{{ $supportMessage->id }}" method="POST">
		@csrf
        @method('PATCH')
		<div class="flex">
			<div class="w-full">
				<div class="text-sm text-gray-500 text-right">
					@lang('admin-support::admin.admin')
				</div>
				<textarea class="w-full rounded-b rounded-tl px-4 py-4 shadow focus:outline-none
					@error('message') border border-red-500 @enderror"
					name="message" rows="3" id="read">{{ old('message') ?? $supportMessage->message }}</textarea>
			</div>
			<div class="pt-6 ml-1">
				<button class="button circle">
					<svg class="fill-current w-5 h-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M13 5.41V21a1 1 0 0 1-2 0V5.41l-5.3 5.3a1 1 0 1 1-1.4-1.42l7-7a1 1 0 0 1 1.4 0l7 7a1 1 0 1 1-1.4 1.42L13 5.4z"/></svg>
				</button>
			</div>
		</div>
	</form>
</div>

@endsection
