<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Xác nhận đặt phòng') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .payment-option {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4">{{ __('Xác nhận đặt phòng') }}</h1>
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">{{ $hotel->name }}</h5>
                <p><strong>{{ __('Phòng') }}:</strong> {{ $room->name }}</p>
                <p><strong>{{ __('Ngày nhận phòng') }}:</strong> {{ $booking->start_date }}</p>
                <p><strong>{{ __('Ngày trả phòng') }}:</strong> {{ $booking->end_date }}</p>
                <p><strong>{{ __('Số người') }}:</strong> {{ $booking->number_of_people }}</p>
                <p><strong>{{ __('Tổng giá') }}:</strong> {{ number_format($booking->total_price) }}đ</p>
                @if ($booking->special_requests)
                    <p><strong>{{ __('Yêu cầu đặc biệt') }}:</strong> {{ $booking->special_requests }}</p>
                @endif

                <div class="payment-option">
                    <h5>{{ __('Chọn phương thức thanh toán') }}</h5>
                    <div class="d-flex gap-3">
                        <!-- Thanh toán qua PayOS -->
                        <form action="{{ route('payments.create') }}" method="POST">
                            @csrf
                            <input type="hidden" name="booking_id" value="{{ $booking->booking_id }}">
                            <input type="hidden" name="payment_method" value="payos">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-credit-card"></i> {{ __('Thanh toán qua PayOS') }}
                            </button>
                        </form>
                        <!-- Thanh toán COD -->
                        <form action="{{ route('payments.create') }}" method="POST">
                            @csrf
                            <input type="hidden" name="booking_id" value="{{ $booking->booking_id }}">
                            <input type="hidden" name="payment_method" value="cod">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-money-bill"></i> {{ __('Thanh toán khi nhận phòng (COD)') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>