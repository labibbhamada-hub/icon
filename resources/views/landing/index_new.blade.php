@extends('layouts.app_new')

@section('title', $conference?->name ?? 'International Conference')

@section('content')

    @include('landing.sections.navbar_new')
    @include('landing.sections.hero_new')
    @include('landing.sections.about_new')
    @include('landing.sections.statistics_new')
    @include('landing.sections.topics_new')
    @include('landing.sections.speakers_new')
    @include('landing.sections.call-for-papers_new')
    @include('landing.sections.important-dates_new')
    @include('landing.sections.registration_new')
    @include('landing.sections.sponsors_new')
    @include('landing.sections.faq_new')
    @include('landing.sections.contact_new')
    @include('landing.sections.footer_new')

@endsection
