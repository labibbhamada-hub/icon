@extends('layouts.admin')

@section('title', 'WhatsApp Group Detail')

@section('header')

    <div class="row">

        <div class="col-sm-6 d-flex align-items-center gap-2">

            <a href="{{ route('admin.conference-whatsapp-groups.index') }}" class="btn btn-secondary btn-sm rounded-0"
                title="Back">
                <i class="bi bi-arrow-left"></i>
            </a>

            <h1 class="mb-0 fs-3">
                WhatsApp Group Detail
            </h1>

        </div>

    </div>

@endsection

@section('content')

    <div class="card rounded-0 overflow-hidden">

        <div class="card-header rounded-0">

            <h3 class="card-title">
                <i class="bi bi-whatsapp me-2"></i>
                WhatsApp Group Information
            </h3>

            <div class="float-end">

                <a href="{{ route('admin.conference-whatsapp-groups.edit', $conferenceWhatsappGroup) }}"
                    class="btn btn-warning rounded-0">
                    <i class="bi bi-pencil me-1"></i>
                    Edit WhatsApp Group
                </a>

            </div>

        </div>

        <div class="card-body">

            <div class="row">

                {{-- Group Information --}}
                <div class="col-md-8">

                    <h2 class="fw-bold mb-2">
                        {{ $conferenceWhatsappGroup->title }}
                    </h2>

                    <div class="mb-3">

                        @if ($conferenceWhatsappGroup->is_active)
                            <span class="badge text-bg-success rounded-0">
                                Active
                            </span>
                        @else
                            <span class="badge text-bg-secondary rounded-0">
                                Inactive
                            </span>
                        @endif

                    </div>

                    <div class="row mb-2">

                        <div class="col-md-4 text-muted">
                            Conference
                        </div>

                        <div class="col-md-8">
                            @if ($conferenceWhatsappGroup->conference)
                                <strong>
                                    {{ $conferenceWhatsappGroup->conference->name }}
                                </strong>

                                <small class="text-muted d-block">
                                    {{ $conferenceWhatsappGroup->conference->short_name }}
                                    ({{ $conferenceWhatsappGroup->conference->year }})
                                </small>
                            @else
                                -
                            @endif
                        </div>

                    </div>

                    <div class="row mb-2">

                        <div class="col-md-4 text-muted">
                            Audience
                        </div>

                        <div class="col-md-8">

                            @if ($conferenceWhatsappGroup->audience === 'presenter')
                                <span class="badge text-bg-primary rounded-0">
                                    Presenter
                                </span>
                            @elseif ($conferenceWhatsappGroup->audience === 'seminar')
                                <span class="badge text-bg-success rounded-0">
                                    Seminar
                                </span>
                            @else
                                <span class="badge text-bg-secondary rounded-0">
                                    Unknown
                                </span>
                            @endif

                        </div>

                    </div>

                    <div class="row mb-2">

                        <div class="col-md-4 text-muted">
                            Group URL
                        </div>

                        <div class="col-md-8">
                            <a href="{{ $conferenceWhatsappGroup->group_url }}" target="_blank" rel="noopener noreferrer">
                                {{ $conferenceWhatsappGroup->group_url }}
                            </a>
                        </div>

                    </div>

                    <div class="row mb-2">

                        <div class="col-md-4 text-muted">
                            Created At
                        </div>

                        <div class="col-md-8">
                            {{ $conferenceWhatsappGroup->created_at->format('d M Y H:i') }}
                        </div>

                    </div>

                    <div class="row mb-2">

                        <div class="col-md-4 text-muted">
                            Last Updated
                        </div>

                        <div class="col-md-8">
                            {{ $conferenceWhatsappGroup->updated_at->format('d M Y H:i') }}
                        </div>

                    </div>

                </div>

                {{-- WhatsApp Icon --}}
                <div class="col-md-4">

                    <div class="border rounded-0 bg-light text-center p-5 h-100">

                        <div class="mb-3">
                            <i class="bi bi-whatsapp display-1 text-muted"></i>
                        </div>

                        <div class="text-muted text-uppercase small">
                            WhatsApp Group
                        </div>

                        @if ($conferenceWhatsappGroup->is_active)
                            <div class="text-success fw-bold mt-2">
                                Active
                            </div>
                        @else
                            <div class="text-muted fw-bold mt-2">
                                Inactive
                            </div>
                        @endif

                    </div>

                </div>

            </div>

        </div>

        <div class="card-body border-top">

            <h5 class="fw-bold mb-2">
                Description
            </h5>

            @if ($conferenceWhatsappGroup->description)
                <div>
                    {!! nl2br(e($conferenceWhatsappGroup->description)) !!}
                </div>
            @else
                <p class="text-muted mb-0">
                    No description available.
                </p>
            @endif

        </div>

    </div>

@endsection
