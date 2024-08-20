@extends('layouts.master')

@section('page_title', 'Edit Question')

@section('content')
<div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="p-5">
                        <h3 class="mb-4">Edit Question for Election</h3>
                        <form action="{{ route('election.updateQuestion') }}" method="POST">
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
                            <input type="hidden" name="question_id" value="{{ $question->id }}">
                            <div class="form-group">
                                <label for="question_text">Question</label>
                                <input type="text" name="question_text" id="question_text" class="form-control" value="{{ old('question_text', $question->question) }}" required>
                            </div>

                            <div class="form-group">
                                <label for="question_type">Question Type</label>
                                <select id="question_type" name="question_type" class="form-control">
                                    <option value="text" {{ $question->type === 'text' ? 'selected' : '' }}>Text Input</option>
                                    <option value="radio" {{ $question->type === 'radio' ? 'selected' : '' }}>Multiple Choice (Single Select)</option>
                                    <option value="checkbox" {{ $question->type === 'checkbox' ? 'selected' : '' }}>Multiple Choice (Multiple Select)</option>
                                    <option value="range" {{ $question->type === 'range' ? 'selected' : '' }}>Range Slider</option>
                                </select>
                            </div>

                            <div id="question-input-container">

                            </div>

                            <div id="options-container" style="display:none;">
                                <div id="additional-options"></div>
                                <button type="button" id="add-option" class="btn btn-secondary">Add Option</button>
                            </div>

                            <button type="submit" class="btn btn-primary mt-4">Update Question</button>
                        </form>
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
        var questionType = $('#question_type').val();
        updateInputFields(questionType);

        $('#question_type').change(function () {
            var newQuestionType = $(this).val();
            updateInputFields(newQuestionType);
        });

        $('#add-option').click(function () {
            var questionType = $('#question_type').val();
            addOption(questionType);
        });

        function updateInputFields(type) {
            var inputContainer = $('#question-input-container');
            var optionsContainer = $('#options-container');
            inputContainer.empty();

            if (type === 'text') {
                optionsContainer.hide();
            } else if (type === 'radio' || type === 'checkbox') {
                optionsContainer.show();
                inputContainer.empty();  // Clear any existing input
                $('#additional-options').empty();  // Clear existing options
                @foreach ($question->options as $index => $option)
                    addOption(type, '{{ $option->option }}', {{ $index + 1 }});
                @endforeach
            } else if (type === 'range') {
                optionsContainer.hide();
                inputContainer.append(`
                    <label>Min: <input type="number" name="range_min" class="form-control" value="{{ old('range_min', $question->range_min) }}" required></label>
                    <label>Max: <input type="number" name="range_max" class="form-control" value="{{ old('range_max', $question->range_max) }}" required></label>
                `);
            } else {
                optionsContainer.hide();
            }
        }

        function addOption(questionType, optionValue = '', index = null) {
            var optionType = (questionType === 'radio') ? 'radio' : 'checkbox';
            var deleteButton = `<button type="button" class="btn btn-danger btn-sm delete-option"><i class="fa fa-trash"></i></button>`;
            var isFirstTwo = index !== null && index <= 2;
            var newOption = $(`
                <div class="form-check mb-2 d-flex align-items-center">
                    <input type="${optionType}" name="answer" class="form-check-input" disabled>
                    <input type="text" name="options[]" class="form-control form-check-label me-2" placeholder="Option" value="${optionValue}" required>
                    ${isFirstTwo ? '' : deleteButton}
                </div>
            `);
            $('#additional-options').append(newOption);

            // Bind click event to delete button
            newOption.find('.delete-option').click(function () {
                $(this).parent().remove();
            });
        }
    });
</script>
@endsection
