@extends('layouts.admin')

@section('title', 'Online Meeting Detail')

@section('header')

    <div class="row">

        <div class="col-sm-6 d-flex align-items-center gap-2">

            <a href="{{ route('admin.conference-online-meetings.index') }}" class="btn btn-secondary btn-sm rounded-0"
                title="Back">
                <i class="bi bi-arrow-left"></i>
            </a>

            <h1 class="mb-0 fs-3">
                Online Meeting Detail
            </h1>

        </div>

        <div class="col-sm-6">

            <nav aria-label="breadcrumb">

                <ol class="breadcrumb float-sm-end">

                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            Dashboard
                        </a>
                    </li>

                    <li class="breadcrumb-item">
                        <a href="{{ route('admin.conference-online-meetings.index') }}">
                            Online Meetings
                        </a>
                    </li>

                    <li class="breadcrumb-item active" aria-current="page">
                        Detail
                    </li>

                </ol>

            </nav>

        </div>

    </div>

@endsection

@section('content')

    <div class="card rounded-0">

        <div class="card-header">

            <h3 class="card-title">
                <i class="bi bi-camera-video me-2"></i>
                Online Meeting Information
            </h3>

            <div class="float-end">

                <a href="{{ route('admin.conference-online-meetings.edit', $conferenceOnlineMeeting) }}"
                    class="btn btn-warning btn-sm rounded-0">
                    <i class="bi bi-pencil me-1"></i>
                    Edit Online Meeting
                </a>

            </div>

        </div>

        <div class="card-body">

            <div class="row">

                {{-- Meeting Information --}}
                <div class="col-md-8">

                    <h2 class="fw-bold mb-2">
                        {{ $conferenceOnlineMeeting->title }}
                    </h2>

                    <div class="mb-3">

                        @if ($conferenceOnlineMeeting->is_active)
                            <span class="badge text-bg-success rounded-0">
                                Active
                            </span>
                        @else
                            <span class="badge text-bg-secondary rounded-0">
                                Inactive
                            </span>
                        @endif

                    </div>

                    <table class="table table-borderless align-middle mb-0">

                        <tbody>

                            <tr>

                                <th width="180">
                                    Conference
                                </th>

                                <td>

                                    @if ($conferenceOnlineMeeting->conference)
                                        <strong>
                                            {{ $conferenceOnlineMeeting->conference->name }}
                                        </strong>

                                        <small class="text-muted d-block">
                                            {{ $conferenceOnlineMeeting->conference->short_name }}
                                            ({{ $conferenceOnlineMeeting->conference->year }})
                                        </small>
                                    @else
                                        -
                                    @endif

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Meeting URL
                                </th>

                                <td>

                                    <a href="{{ $conferenceOnlineMeeting->meeting_url }}" target="_blank"
                                        rel="noopener noreferrer">
                                        {{ $conferenceOnlineMeeting->meeting_url }}
                                    </a>

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Meeting ID
                                </th>

                                <td>

                                    @if ($conferenceOnlineMeeting->meeting_id)
                                        <code>
                                            {{ $conferenceOnlineMeeting->meeting_id }}
                                        </code>
                                    @else
                                        -
                                    @endif

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Passcode
                                </th>

                                <td>

                                    @if ($conferenceOnlineMeeting->passcode)
                                        <code>
                                            {{ $conferenceOnlineMeeting->passcode }}
                                        </code>
                                    @else
                                        -
                                    @endif

                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Created At
                                </th>

                                <td>
                                    {{ $conferenceOnlineMeeting->created_at->format('d M Y H:i') }}
                                </td>

                            </tr>

                            <tr>

                                <th>
                                    Last Updated
                                </th>

                                <td>
                                    {{ $conferenceOnlineMeeting->updated_at->format('d M Y H:i') }}
                                </td>

                            </tr>

                        </tbody>

                    </table>

                </div>

                {{-- Meeting Icon --}}
                <div class="col-md-4">

                    <div class="border rounded-0 bg-light text-center p-5 h-100">

                        <div class="mb-3">
                            <i class="bi bi-camera-video display-1 text-muted"></i>
                        </div>

                        <div class="text-muted text-uppercase small">
                            Online Meeting
                        </div>

                        @if ($conferenceOnlineMeeting->is_active)
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
                Instructions
            </h5>

            @if ($conferenceOnlineMeeting->instructions)
                <div>
                    {!! nl2br(e($conferenceOnlineMeeting->instructions)) !!}
                </div>
            @else
                <p class="text-muted mb-0">
                    No instructions available.
                </p>
            @endif

        </div>

        <div class="card-footer">

            <a href="{{ route('admin.conference-online-meetings.index') }}" class="btn btn-secondary btn-sm rounded-0">
                <i class="bi bi-arrow-left me-1"></i>
                Back to Online Meetings
            </a>

        </div>

    </div>

@endsection
