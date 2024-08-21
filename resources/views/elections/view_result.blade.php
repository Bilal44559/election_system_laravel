@extends('layouts.master')

@section('page_title', 'Voting Results')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="container">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="mt-3 mr-3">
                            <a href="{{ route('election') }}" class="btn btn-primary btn-sm float-right">Back</a>
                        </div>
                        <div class="p-5">
                            <h2 class="text-center">{{ $election->title }} - Results</h2>
                            <h5 class="text-center">{{ count($votes) }} Candidates Voted</h5>
                            <hr>

                            <div class="accordion" id="userResultsAccordion">
                                @foreach ($votes as $vote)
                                    <div class="card">
                                        <div class="card-header" id="heading-{{ $vote->id }}">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse-{{ $vote->id }}" aria-expanded="true" aria-controls="collapse-{{ $vote->id }}">
                                                    {{$loop->iteration}} - {{ $vote->user->name }} - Voted on {{ $vote->created_at->format('M d, Y') }}
                                                </button>
                                            </h2>
                                        </div>

                                        <div id="collapse-{{ $vote->id }}" class="collapse" aria-labelledby="heading-{{ $vote->id }}" data-parent="#userResultsAccordion">
                                            <div class="card-body">
                                                @foreach($vote->answers->groupBy('question_id') as $questionId => $answers)
                                                    <div class="question-section my-4">
                                                        <h5>Q: {{ $answers->first()->question->question }}</h5>
                                                        <span>Answer:</span>
                                                        @if(count($answers) > 1)
                                                        <ul>
                                                            @foreach($answers as $answer)
                                                            <li>{{ $answer->answer }}</li>
                                                            @endforeach
                                                        </ul>
                                                        @else
                                                        <p>{{ $answers->first()->answer }}</p>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            {{-- <a href="{{ route('elections.index') }}" class="btn btn-secondary mt-4">Back to Elections</a> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function () {
        // Initialize the accordion
        $('#userResultsAccordion').accordion();
    });
</script>
@endsection
