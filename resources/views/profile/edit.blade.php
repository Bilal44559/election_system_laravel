@extends('layouts.master')

@section('page_title', 'Profile')

@section('content')
<div class="container">

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="mt-5 ml-5">{{ __("Update your account's profile information and email address.") }}</h3>
                    @if (session('status') === 'profile-updated')
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                        {{ __('Saved.') }}
                    </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="p-5">
                        <form class="user" action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('patch')
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control form-control-user @error('name') is-invalid @enderror" id="name" placeholder="Name">
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="email">Name</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control form-control-user @error('email') is-invalid @enderror" id="email" placeholder="Email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-user">
                                {{ __('Save') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="mt-5 ml-5">{{ __('Update Password') }}</h3>
                    @if (session('status') === 'password-updated')
                    <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
                        {{ __('Saved.') }}
                    </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="p-5">
                        <form class="user" action="{{ route('password.update') }}" method="POST">
                            @csrf
                            @method('put')
                            <div class="form-group">
                                <label for="current_password">Current Password</label>
                                <input type="password" name="current_password" value="{{ old('current_password', $user->current_password) }}" class="form-control form-control-user @error('current_password') is-invalid @enderror" id="current_password" placeholder="Current Password">
                                @error('current_password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password">New Password</label>
                                <input type="password" name="password" value="{{ old('password') }}" class="form-control form-control-user @error('password') is-invalid @enderror" id="password" placeholder="New Password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="password_confirmation">Confirm Password</label>
                                <input type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" class="form-control form-control-user @error('password_confirmation') is-invalid @enderror" id="password_confirmation" placeholder="Current Password">
                                @error('password_confirmation')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary btn-user">
                                {{ __('Save') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card o-hidden border-0 shadow-lg my-5">
        <div class="card-body p-0">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="mt-5 ml-5">{{ __('Delete Account') }}</h3>
                    @if (session('status') === 'password-updated')
                    <div class="alert alert-success alert-dismissible fade show alert-fadeout" role="alert" id="success-alert">
                        {{ __('Saved.') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="p-5">
                        <button
                            type="button"
                            class="btn btn-danger deleteAccountModalOpenBtn"
                        >
                            {{ __('Delete Account') }}
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="confirm-user-deletion" tabindex="-1" aria-labelledby="confirm-user-deletion-label" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="confirm-user-deletion-label">{{ __('Delete Account') }}</h5>
                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <form method="post" action="{{ route('profile.destroy') }}">
                                            @csrf
                                            @method('delete')

                                            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                                {{ __('Are you sure you want to delete your account?') }}
                                            </h2>

                                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                                            </p>

                                            <div class="mt-6">
                                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                                <input
                                                    id="password"
                                                    name="password"
                                                    type="password"
                                                    class="form-control"
                                                    placeholder="{{ __('Password') }}"
                                                />
                                                @if ($errors->userDeletion->has('password'))
                                                    <span class="text-danger">
                                                        {{ $errors->userDeletion->first('password') }}
                                                    </span>
                                                @endif
                                            </div>

                                            {{-- <div class="mt-6 d-flex justify-content-end">
                                                <button type="button" class="btn btn-secondary me-3" data-bs-dismiss="modal">
                                                    {{ __('Cancel') }}
                                                </button>
                                                <button type="submit" class="btn btn-danger">
                                                    {{ __('Delete Account') }}
                                                </button>
                                            </div> --}}
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                                <button class="btn btn-danger" type="submit"> {{ __('Delete Account') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Modal -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
