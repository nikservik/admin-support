@extends('admin-dashboard::layout')

@section('content')
<h1 class="page-header">
    <a href="{{ config('admin-support.route') }}/dialog/{{ $user->id }}" class="text-white">@lang('admin-support::admin.list-title')</a>
</h1>
<h2 class="sub-header">
	@lang('admin-support::admin.edit-title')
    {{ $user->name }}
</h2>

<div class="my-4 mx-2 md:mx-10 pl-10">
	<form action="/{{ config('admin-support.route') }}/message/{{ $supportMessage->id }}" method="POST">
		@csrf
        @method('PATCH')
		<div class="flex">
			<div class="w-full">
				<div class="text-sm text-gray-500 text-right">
					@lang('admin-support::admin.admin')
				</div>
				<textarea class="w-full rounded-b rounded-tl px-4 py-4 border-2 focus:outline-none
					@error('message') border-red-500 @else border-gray-300 @enderror"
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
