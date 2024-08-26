@extends('layouts.web')

@section('page_title', 'Election Page')

@section('content')
<div class="container mt-5">

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <div class="p-5">
                <!-- Election Title -->
                <div class="text-center">
                    <h1 class="display-4 font-weight-bold">{{ $election->title }}</h1>
                </div><hr>

                <!-- Election Date -->
                <div class="row mt-3">
                    <div class="col text-center">
                        <h5 class="font-weight-bold">
                            {{ date("F j,Y h:i a",strtotime($election->start_date)) }} - {{ date("F j,Y h:i a",strtotime($election->end_date)) }}
                        </h5>
                        <p class="text-muted">Voting period</p>
                    </div>
                </div>

                <!-- Election Description -->
                @if(!empty($election->description))
                <div class="row mt-5">
                    <div class="col text-center">
                        <h3 class="font-weight-bold">About the Election</h3>
                        <p class="text-muted">
                            {{ $election->description }}
                        </p>
                    </div>
                </div>
                @endif

                <!-- Election Questions -->
                <div class="row mt-5">
                    <div class="col text-center">
                        <h3 class="font-weight-bold">Election Questions</h3>
                        @if(count($election->questions->where('is_active','1')) > 0)
                        <ul class="text-left">
                            @foreach($election->questions->where('is_active','1') as $question)
                            <li>{{ $question->question }}</li>
                            @endforeach
                        </ul>
                        @else
                        <p>Questions are not available</p>
                        @endif
                    </div>
                </div>

                <!-- QR Code and Direct Link -->
                <div class="row mt-4">
                    <!-- QR Code Section -->
                    <div class="col-md-6 text-center">
                        <h4>Scan to Vote</h4>
                        {!! QrCode::size(200)->generate(route('user.vote_form',$election->id)) !!}
                        <p class="mt-2">Scan the QR code to visit the election page on your device.</p>
                    </div>

                    <!-- Direct Link Section -->
                    <div class="col-md-6 text-center">
                        <h4>Direct Link</h4>
                        <a href="{{ route('user.vote_form',$election->id) }}" class="btn btn-primary btn-lg">Go to Election Page</a>
                        <p class="mt-2">Click the link to directly access the election page.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
