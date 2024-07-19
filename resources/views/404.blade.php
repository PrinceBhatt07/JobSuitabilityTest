{{-- @extends('layout.layout') --}}
<div style="text-align: center">
@if(Session::has('message'))
<p class="h1 text-danger">{{ Session::get('message') }}</p>
@else
<h1>404- Page is Expired</h1>
{{-- @dd($message) --}}
<p>{{ $message ?? $message }}</p>
@endif
</div>
