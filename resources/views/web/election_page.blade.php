@extends('layouts.web')

@section('page_title', 'Election Page')

@section('content')
<div class="container-fluid mt-5">
    <div class="card o-hidden border-0 shadow-lg my-5 bg-primary">
        <div class="card-body p-0">
            <div class="p-5">
                <div class="row">
                    @if(count($questions) == 0)
                        <div class="col-md-12">
                            <h3 class="text-center text-white">There is no question yet.</h3>
                        </div>
                    @else
                    <div class="col-md-3">
                        <!-- Election Title -->
                        <div class="text-center">
                            <h5 class="font-weight-bold text-white">{{ $election->title }}</h5>
                            <h4 class="text-white mt-5 mb-3">Join at</h4>
                        </div>

                        <div class="card o-hidden border-0 shadow-lg text-center pt-4 pb-4">
                            <div class="card-body p-0">
                                {!! QrCode::size(200)->generate(route('user.vote_form', $election->id)) !!}
                            </div>
                        </div>
                        <div class="text-center">
                            <p class="mt-2 text-white mt-3">Scan the QR code to visit the election page on your device.</p>
                        </div>
                        <!-- QR Code and Direct Link -->
                        {{-- <div class="row mt-4 pt-5 pb-5">
                            <div class="col-md-12 text-center">
                                <h4 class="text-white">Join at</h4>
                                {!! QrCode::size(200)->generate(route('user.vote_form', $election->id)) !!}
                                <p class="mt-2 text-white">Scan the QR code to visit the election page on your device.</p>
                            </div>
                        </div> --}}
                    </div>
                    <div class="col-md-9 bg-white rounded p-5">
                        <div class="row align-items-center">
                            <div class="col-md-2 text-right">
                                <button id="prev-btn" class="btn btn-primary" disabled>
                                    <i class="fas fa-arrow-left"></i>
                                </button>
                            </div>
                            <div class="col-md-8 text-center">
                                <!-- Question Section -->
                                <h3 id="question-text" class="font-weight-bold text-dark mb-0">
                                    {{ $questions[0]['text'] }}
                                </h3>
                                <p class="text-dark">Question <span id="question-number">1</span> of {{ count($questions) }}</p>
                            </div>
                            <div class="col-md-2 text-left">
                                <button id="next-btn" class="btn btn-primary">
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div><hr>

                        <!-- Answers with Progress Bar -->
                        <div id="options-container" class="mt-4">
                            @foreach($questions[0]['options'] as $option)
                                <div class="mb-2">
                                    <p class="mb-1 text-dark" style="color: #000 !important;">{{ $option['text'] }}</p>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: {{ $option['percent'] }}%;" aria-valuenow="{{ $option['percent'] }}" aria-valuemin="0" aria-valuemax="100">
                                            {{ $option['percent'] }}%
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Store questions data in a hidden div -->
                        <div id="questions-data" data-questions='@json($questions)'></div>
                        <div id="options-container" class="mt-4">
                            <!-- Placeholder for message when no options are available -->
                            <p id="no-options-message" class="text-center text-dark d-none">No answer available for this question.</p>
                        </div>

                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const questionsData = JSON.parse(document.getElementById('questions-data').dataset.questions);
        let currentQuestionIndex = 0;

        const questionText = document.getElementById('question-text');
        const questionNumber = document.getElementById('question-number');
        const optionsContainer = document.getElementById('options-container');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');

        function updateQuestion() {
            const currentQuestion = questionsData[currentQuestionIndex];
            questionText.textContent = currentQuestion.text;
            questionNumber.textContent = currentQuestionIndex + 1;

            optionsContainer.innerHTML = '';

            if (currentQuestion.options.length > 0) {
                // Hide no options message if options are available
                document.getElementById('no-options-message').classList.add('d-none');

                // Display options and progress bars
                currentQuestion.options.forEach(option => {
                    const optionElement = document.createElement('div');
                    optionElement.classList.add('mb-2');

                    const optionText = document.createElement('p');
                    optionText.classList.add('mb-1');
                    optionText.textContent = option.text;

                    const progressBarContainer = document.createElement('div');
                    progressBarContainer.classList.add('progress');

                    const progressBar = document.createElement('div');
                    progressBar.classList.add('progress-bar', 'progress-bar-striped', 'bg-success', 'progress-bar-animated');
                    progressBar.style.width = `${option.percent}%`;
                    progressBar.setAttribute('role', 'progressbar');
                    progressBar.setAttribute('aria-valuenow', option.percent);
                    progressBar.setAttribute('aria-valuemin', 0);
                    progressBar.setAttribute('aria-valuemax', 100);
                    progressBar.textContent = `${option.percent}%`;

                    progressBarContainer.appendChild(progressBar);
                    optionElement.appendChild(optionText);
                    optionElement.appendChild(progressBarContainer);
                    optionsContainer.appendChild(optionElement);
                });
            } else {
                // Show no options message if no options are available
                const noOptionsMessage = document.getElementById('no-options-message');
                noOptionsMessage.classList.remove('d-none');
                noOptionsMessage.textContent = 'No options available for this question.';
            }

            // Disable or enable buttons based on question index
            prevBtn.disabled = currentQuestionIndex === 0;
            nextBtn.disabled = currentQuestionIndex === questionsData.length - 1;
        }


        // Event listeners for buttons
        prevBtn.addEventListener('click', function () {
            if (currentQuestionIndex > 0) {
                currentQuestionIndex--;
                updateQuestion();
            }
        });

        nextBtn.addEventListener('click', function () {
            if (currentQuestionIndex < questionsData.length - 1) {
                currentQuestionIndex++;
                updateQuestion();
            }
        });

        // Initialize with the first question
        updateQuestion();
    });
    </script>

@endsection
