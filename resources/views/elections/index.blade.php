@extends('layouts.master')

@section('page_title', 'Elections')

@section('content')
<div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="p-5">
                        <a href="{{ route('election.create') }}" class="btn btn-primary btn-sm float-right">Create Election</a>
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Type</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(count($elections) > 0)
                                    @foreach($elections as $election)
                                    <tr>
                                        <td>{{ $election->title }}</td>
                                        <td>{{ $election->description }}</td>
                                        <td>{{ $election->start_date }}</td>
                                        <td>{{ $election->end_date }}</td>
                                        <td>
                                            <ul>
                                            @foreach(explode(",",$election->type) as $election_type)
                                                <li>{{ strtoupper(str_replace("-"," ",$election_type)) }}</li>
                                            @endforeach
                                            </ul>
                                        </td>
                                        <td>
                                            <a href="{{ route('election.addQuestion', $election->id) }}" class="btn btn-success btn-sm">
                                                <i class="fas fa-plus-circle"></i> Add Question
                                            </a>
                                            <a href="{{ route('election.detail', $election->id) }}" class="btn btn-info btn-sm">
                                                <i class="fas fa-info-circle"></i>
                                            </a>
                                            <a href="{{ route('election.edit', $election->id) }}" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm deleteBtn" data-id={{$election->id}} data-route={{ route('election.destroy') }}>
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
