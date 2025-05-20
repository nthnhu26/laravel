<!-- resources/views/auth/verify.blade.php -->
@extends('auth.layouts')

@section('content')
<p>{{ __('Trước khi tiếp tục, vui lòng kiểm tra email của bạn để lấy liên kết xác thực.') }}</p>
<p>{{ __('Nếu bạn không nhận được email') }},</p>

<form method="POST" action="{{ route('verification.send') }}">
    @csrf
    <div class="form-group">
        <label for="email">Email</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

        @error('email')
        <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    <div class="form-group mt-3">
        <button type="submit" class="btn btn-primary">
            {{ __('Gửi lại email xác thực') }}
        </button>
    </div>
</form>
@endsection