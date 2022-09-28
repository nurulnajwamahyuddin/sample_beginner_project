@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">An email with verification code has been sent to {{$email}}</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('register.code') }}">
                            @csrf



                                    <input type="hidden"  name="email" value="{{ $email }}" >


                            <div class="row mb-3">
                                <label for="code" class="col-md-4 col-form-label text-md-end">Verification Code</label>

                                <div class="col-md-6">
                                    <input id="code"    type="text"  name="code" value="{{ old('code') }}" required>

                                </div>
                            </div>


                            <div class="row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Verify Code
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
