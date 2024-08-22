@extends('layouts.master')

@section('page_title', 'Add Question')

@section('content')
<div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="p-5">
                        <h3 class="mb-4">Add Question to Election</h3>
                        <form action="{{ route('election.questionStore') }}" method="POST">
                            @csrf
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <input type="hidden" name="election_id" value="{{ $election->id }}">
                            <div class="form-group">
                                <label for="question_text">Question</label>
                                <input type="text" name="question_text" id="question_text" class="form-control" required>
                            </div>

                            <div class="form-group">
                                <label for="question_type">Question Type</label>
                                <select id="question_type" name="question_type" class="form-control">
                                    <option value="text">Text Input</option>
                                    <option value="radio">Multiple Choice (Single Select)</option>
                                    <option value="checkbox">Multiple Choice (Multiple Select)</option>
                                    <option value="range">Range Slider</option>
                                </select>
                            </div>

                            <div id="question-input-container">
                                <!-- Dynamic input fields will be appended here -->
                            </div>

                            <div id="options-container" style="display:none;">
                                <div id="additional-options"></div>
                                <button type="button" id="add-option" class="btn btn-secondary">Add Option</button>
                            </div>


                            <button type="submit" class="btn btn-primary mt-4">Save Question</button>
                        </form>


                        <!-- Table to display existing questions and options -->
                        <h3 class="mt-5">Existing Questions</h3>
                        <table class="table table-bordered table-striped mt-3">
                            <thead>
                                <tr>
                                    <th>SR</th>
                                    <th>Question</th>
                                    <th>Type</th>
                                    <th>Options</th>
                                    <th>Range Min</th>
                                    <th>Range Max</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(count($questions) > 0)
                                @foreach($questions as $question)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $question->question }}</td>
                                    <td>
                                        @switch($question->type)
                                            @case('text') Text Input @break
                                            @case('radio') Multiple Choice (Single Select) @break
                                            @case('checkbox') Multiple Choice (Multiple Select) @break
                                            @case('range') Range Slider @break
                                            @default Unknown
                                        @endswitch
                                    </td>
                                    <td>
                                        @if($question->type === 'radio' || $question->type === 'checkbox')
                                            <ul>
                                                @foreach($question->options as $option)
                                                    <li>{{ $option->option }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if($question->type === 'range')
                                            {{ $question->range_min }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        @if($question->type === 'range')
                                            {{ $question->range_max }}
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td>
                                        <div class="switch mt-3">
                                            <button type="button" data-id="{{ $question->id }}" data-route={{ route('election.questionStatus') }} class="btn btn-sm btn-toggle statusBtn {{ $question->is_active == "1" ? 'active' : '' }}" data-toggle="button" aria-pressed="{{ $question->is_active == "1" ? 'true' : '' }}" autocomplete="off">
                                              <div class="handle"></div>
                                            </button>
                                          </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('election.editQuestion', ['election_id' => $election->id, 'question_id' => $question->id]) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger btn-sm deleteBtn" data-id={{$question->id}} data-route={{ route('election.questionDestroy') }}>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                                @else
                                <tr>
                                    <td colspan="8" class="text-center">No questions found.</td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
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
        $('#question_type').change(function () {
            var questionType = $(this).val();
            var inputContainer = $('#question-input-container');
            var optionsContainer = $('#options-container');
            inputContainer.empty();  // Clear previous inputs

            if (questionType === 'text') {
                optionsContainer.hide();
                inputContainer.append('<input type="text" name="answer" class="form-control" placeholder="Your answer" required>');
            } else if (questionType === 'radio' || questionType === 'checkbox') {
                optionsContainer.show();
                inputContainer.empty();  // Clear any existing input
                $('#additional-options').empty();  // Clear existing options
                addOption(questionType); // Add initial option
            } else if (questionType === 'range') {
                optionsContainer.hide();
                inputContainer.append(`
                    <label>Min: <input type="number" name="range_min" class="form-control" value="0" required></label>
                    <label>Max: <input type="number" name="range_max" class="form-control" value="100" required></label>
                `);
            } else {
                optionsContainer.hide();
            }
        });

        $('#add-option').click(function () {
            var questionType = $('#question_type').val();
            addOption(questionType);
        });

        function addOption(questionType) {
            var optionType = (questionType === 'radio') ? 'radio' : 'checkbox';
            var newOption = $('<div class="form-check mb-2">').append(`
                <input type="${optionType}" name="answer" class="form-check-input" disabled>
                <input type="text" name="options[]" class="form-control form-check-label" placeholder="Option" required>
            `);
            $('#additional-options').append(newOption);
        }
    });
</script>
@endsection
