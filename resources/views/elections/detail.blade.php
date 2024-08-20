@extends('layouts.master')

@section('page_title', 'Election Details')
@section('style_script')
<style>
    label{
        font-weight: 800;
    }
</style>
@endsection
@section('content')
<div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-lg-12">
                    <div class="mt-3 mr-3">
                        <a href="{{ route('election') }}" class="btn btn-primary btn-sm float-right">Back</a>
                    </div>
                    <div class="p-5">

                        <h2 class="mb-4">{{ $election->title }}</h2><hr>

                        <div class="form-group">
                            <label>Description:</label>
                            <p>{{ $election->description }}</p>
                        </div>

                        <div class="form-group">
                            <label>Start Date:</label>
                            <p>{{ $election->start_date }}</p>
                        </div>

                        <div class="form-group">
                            <label>End Date:</label>
                            <p>{{ $election->end_date }}</p>
                        </div>

                        <div class="form-group">
                            <label>Type:</label>
                            <ul>
                            @foreach(explode(",", $election->type) as $election_type)
                                <li>{{ strtoupper(str_replace("-", " ", $election_type)) }}</li>
                            @endforeach
                            </ul>
                        </div>

                        <div class="form-group">
                            <label>Candidates:</label>
                            <table class="table table-bordered table-striped datatable">
                                <thead>
                                    <tr>
                                        <th>Sr</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($candidates as $candidate)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $candidate->name }}</td>
                                        <td>{{ $candidate->email }}</td>
                                    </tr>
                                    @endforeach
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
