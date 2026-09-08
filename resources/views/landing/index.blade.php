@extends('layouts.app')

@section('title', $conference?->name ?? 'International Conference')

@section('content')

    @include('landing.sections.navbar')

    @include('landing.sections.hero')

    @include('landing.sections.about')

    @include('landing.sections.topics')

    @include('landing.sections.speakers')

    @include('landing.sections.important-dates')

    @include('landing.sections.call-for-papers')

    @include('landing.sections.registration')

    @include('landing.sections.sponsors')

    @include('landing.sections.contact')

    @include('landing.sections.footer')

@endsection
