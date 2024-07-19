@extends('layout/layout')

@section('content')

<div class="container">
<div class="text-center">
<h2>Thank you for submitting your Exam , {{ $name }}</h2>
<p>We will review your Exam, and update you soon</p>
{{-- <a href="/dashboard" class="btn btn-info">Go Back</a> --}}
</div>
</div>
@endsection
