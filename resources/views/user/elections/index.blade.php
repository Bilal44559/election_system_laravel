@extends('layouts.master')

@section('page_title', 'Elections')

@section('content')
<div class="container">
    <div class="row">
        @foreach($elections as $election)
            <div class="col-md-4 mb-4">
                <div class="container">
                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="p-5">
                                        <h5 class="card-title">{{ $election->title }}</h5>
                                        <hr>
                                        <p class="card-text">
                                            <strong>Start Date:</strong> {{ $election->start_date }}<br>
                                            <strong>End Date:</strong> {{ $election->end_date }}
                                        </p>
                                        @if(count($election->questions->where('is_active','1')) == 0)
                                            <p class="text-danger">There are no questions for this election yet.</p>
                                        @else

                                            @if($election->end_date < date('Y-m-d H:i:s'))
                                                <p class="text-danger">Expired Election</p>
                                            @else
                                                @if(userCastVote($election->id))
                                                <p class="text-success">Your vote has been cast</p>
                                                @else
                                                <a href="{{ route('user.vote_form', $election->id) }}" class="btn btn-primary">Vote</a>
                                                @endif
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection
