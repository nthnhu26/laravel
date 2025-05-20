@extends('auth.layouts')

@section('title', 'Quên mật khẩu')

@section('content')
<h1 class="text-center">@lang('forgotpassword')</h1>
<a href="{{ route('home') }}" class="close-btn text-decoration-none">&times;</a>
<div class="text-center">
    <span>@lang('sendresetlink')</span>
</div>
<form action="{{ route('password.email') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror"
            id="email" name="email" placeholder="@lang('enteryouremail')" value="{{ old('email') }}" required>
        @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
    <button type="submit" class="btn btn-primary w-100">@lang('passwordresetlink')</button>
</form>
<div class="text-center mt-3 mb-3">
    <a href="{{ route('login') }}" class="text-decoration-none">@lang('signinnow')</a>
</div>
@endsection