<!-- resources/views/results/index.blade.php -->

@extends('layouts.web')

@section('page_title', 'Election Results')

@section('content')
<div class="container">
    <h1 class="my-4 text-center">Election Results</h1>

    <div class="row">
        @foreach($chartData as $election)
        <div class="col-md-12">
            <div class="card my-4">
                <div class="card-header">
                    <h2>{{ $election['title'] }}</h2>
                </div>
                <div class="card-body">
                    <div class="row">
                    @foreach($election['questions'] as $question)
                        <div class="mb-4 col-md-4">
                            <h5>{{ $question['question'] }}</h5>
                            <canvas id="chart-{{ $loop->parent->index }}-{{ $loop->index }}" width="400" height="200"></canvas>
                        </div>
                    @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chartData = @json($chartData);

        chartData.forEach((election, electionIndex) => {
            election.questions.forEach((question, questionIndex) => {
                const ctx = document.getElementById(`chart-${electionIndex}-${questionIndex}`).getContext('2d');
                new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: question.answers.map(answer => answer.answer),
                        datasets: [{
                            data: question.answers.map(answer => answer.count),
                            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'],
                            hoverBackgroundColor: ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40']
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            });
        });
    });
</script>
@endsection
