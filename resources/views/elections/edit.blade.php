<!-- resources/views/elections/edit.blade.php -->
@extends('layouts.master')

@section('page_title', 'Edit Election')

@section('content')
<div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
                <div class="col-lg-12">
                    <div class="mt-3 mr-3">
                        <a href="{{ route('election') }}" class="btn btn-primary btn-sm float-right">Back</a>
                    </div>
                    <div class="p-5">
                        <form class="user" action="{{ route('election.update') }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $election->id }}">
                            <div class="form-group">
                                <label for="title">Title</label>
                                <input type="text" name="title" class="form-control form-control-user @error('title') is-invalid @enderror" id="title" placeholder="Election Title" value="{{ old('title', $election->title) }}">
                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description">Description</label>
                                <textarea name="description" class="form-control form-control-user @error('description') is-invalid @enderror" id="description" rows="3" placeholder="Election Description">{{ old('description', $election->description) }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="start_date">Start Date</label>
                                <input type="datetime-local" name="start_date" class="form-control form-control-user @error('start_date') is-invalid @enderror" id="start_date" value="{{ old('start_date', \Carbon\Carbon::parse($election->start_date)->format('Y-m-d\TH:i')) }}">
                                @error('start_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="end_date">End Date</label>
                                <input type="datetime-local" name="end_date" class="form-control form-control-user @error('end_date') is-invalid @enderror" id="end_date" value="{{ old('end_date', \Carbon\Carbon::parse($election->end_date)->format('Y-m-d\TH:i')) }}">
                                @error('end_date')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="type">Type</label>
                                <div>
                                    <div class="form-check">
                                        <input class="form-check-input @error('type') is-invalid @enderror" type="checkbox" name="type[]" id="multiple-choice" value="multiple-choice" {{ (is_array(old('type', explode(',', $election->type))) && in_array('multiple-choice', old('type', explode(',', $election->type)))) ? 'checked' : '' }} style="border-radius:20px;">
                                        <label class="form-check-label" for="multiple-choice">
                                            Multiple Choice
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input @error('type') is-invalid @enderror" type="checkbox" name="type[]" id="first-past-the-post" value="first-past-the-post" {{ (is_array(old('type', explode(',', $election->type))) && in_array('first-past-the-post', old('type', explode(',', $election->type)))) ? 'checked' : '' }} style="border-radius:20px;">
                                        <label class="form-check-label" for="first-past-the-post">
                                            First Past The Post
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input @error('type') is-invalid @enderror" type="checkbox" name="type[]" id="preferential" value="preferential" {{ (is_array(old('type', explode(',', $election->type))) && in_array('preferential', old('type', explode(',', $election->type)))) ? 'checked' : '' }} style="border-radius:20px;">
                                        <label class="form-check-label" for="preferential">
                                            Preferential Voting
                                        </label>
                                    </div>
                                </div>
                                @error('type')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="candidates">Candidates/Choices</label>
                                <select class="select2-multiple form-control" required name="candidates[]" multiple="multiple" style="width:100%;">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ $election->candidates->contains($user->id) ? 'selected' : '' }}>{{ $user->name }}</option>
                                    @endforeach
                                </select>
                                @error('candidates')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                Update Election
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
