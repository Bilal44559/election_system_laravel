@extends('layouts.master')

@section('page_title', 'Vote Form')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mb-4">
            <div class="container">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="p-5">
                            <form id="vote-form" action="{{ route('user.vote.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="election_id" value="{{ $election->id }}">

                                <div id="form-steps">

                                </div>

                                <div class="form-navigation">
                                    <button type="button" id="prev-btn" class="btn btn-secondary">Previous</button>
                                    <button type="button" id="next-btn" class="btn btn-primary">Next</button>
                                    <button type="submit" id="submit-btn" class="btn btn-success" style="display: none;">Submit</button>
                                </div>
                            </form>
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
    const formSteps = $('#form-steps');
    const questions = @json($questions); // Pass questions from controller
    let currentStep = 0;

    // Object to store answers
    const answers = {};

    function renderStep(step) {
        formSteps.empty();
        const question = questions[step];
        if (question) {
            let questionHtml = `<div class="form-group">
                <label>${question.text}</label>`;

            if (question.type === 'text') {
                questionHtml += `<input type="text" name="answers[${question.id}]" class="form-control" required value="${answers[question.id] || ''}">`;
            } else if (question.type === 'radio' || question.type === 'checkbox') {
                question.options.forEach(option => {
                    const checked = (answers[question.id] || []).includes(option) ? 'checked' : '';
                    questionHtml += `<div class="form-check">
                        <input type="${question.type}" name="answers[${question.id}][]" value="${option}" class="form-check-input" ${checked} required>
                        <label class="form-check-label">${option}</label>
                    </div>`;
                });
            } else if (question.type === 'range') {
                const rangeValue = answers[question.id] || question.range_min;
                questionHtml += `
                    <label>Range: ${question.range_min} - ${question.range_max}</label>
                    <input type="range" name="answers[${question.id}]" min="${question.range_min}" max="${question.range_max}" class="form-control" required value="${rangeValue}">
                    <span id="range-value">${rangeValue}</span>
                `;
            }

            questionHtml += `</div>`;
            formSteps.html(questionHtml);

            // Update range value display and store value
            if (question.type === 'range') {
                const rangeInput = formSteps.find('input[type="range"]');
                const rangeValue = formSteps.find('#range-value');

                rangeInput.on('input', function () {
                    rangeValue.text(this.value);
                    answers[question.id] = this.value;
                });

                // Initialize display with the current value
                rangeValue.text(rangeInput.val());
            }

            // Update stored value on input change
            formSteps.on('change', 'input', function () {
                const name = $(this).attr('name');
                if (name) {
                    const nameParts = name.match(/answers\[(\d+)\](?:\[\])?/);
                    if (nameParts) {
                        const questionId = nameParts[1];
                        if (question.type === 'radio' || question.type === 'checkbox') {
                            const checkedOptions = [];
                            formSteps.find(`input[name="answers[${questionId}][]"]:checked`).each(function () {
                                checkedOptions.push($(this).val());
                            });
                            answers[questionId] = checkedOptions;
                        } else {
                            answers[questionId] = $(this).val();
                        }
                    }
                }
            });

            $('#prev-btn').toggle(step > 0);
            $('#next-btn').toggle(step < questions.length - 1);
            $('#submit-btn').toggle(step === questions.length - 1);
        }
    }

    function validateCurrentStep() {
        const question = questions[currentStep];
        let isValid = true;
        if (question) {
            if (question.type === 'text') {
                isValid = $('input[name="answers[' + question.id + ']"]').val().trim() !== '';
            } else if (question.type === 'radio' || question.type === 'checkbox') {
                isValid = $('input[name="answers[' + question.id + '][]"]:checked').length > 0;
            } else if (question.type === 'range') {
                const rangeValue = $('input[name="answers[' + question.id + ']"]').val();
                isValid = rangeValue !== '' && parseInt(rangeValue) >= question.range_min && parseInt(rangeValue) <= question.range_max;
            }
        }
        return isValid;
    }

    $('#next-btn').click(function () {
        if (validateCurrentStep()) {
            if (currentStep < questions.length - 1) {
                currentStep++;
                renderStep(currentStep);
            }
        } else {
            showErrorMessage('Please fill out this step before moving on.');
        }
    });

    $('#prev-btn').click(function () {
        if (currentStep > 0) {
            currentStep--;
            renderStep(currentStep);
        }
    });

    function showErrorMessage(message) {
        $('#form-steps').prepend(`<div class="alert alert-danger">${message}</div>`);
    }

    $('#vote-form').submit(function (e) {
        // Ensure that all the latest answers are saved to the form
        Object.keys(answers).forEach(questionId => {
            const answerValue = answers[questionId];
            if (Array.isArray(answerValue)) {
                // For checkbox or radio options, create multiple hidden inputs
                answerValue.forEach(value => {
                    $('<input>').attr({
                        type: 'hidden',
                        name: `answers[${questionId}][]`,
                        value: value
                    }).appendTo('#vote-form');
                });
            } else {
                $('<input>').attr({
                    type: 'hidden',
                    name: `answers[${questionId}]`,
                    value: answerValue
                }).appendTo('#vote-form');
            }
        });
    });

    renderStep(currentStep); // Initialize form with the first question
});


</script>
@endsection
