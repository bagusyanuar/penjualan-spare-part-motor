@extends('customer.layout')

@section('content')
    <main class="py-5">
        <div class="container">
            <div class="row d-flex justify-content-center">
                <div class="col-lg-8 col-12">
                    <h2 class="fs-5 py-4 text-center">
                        Integrasi Midtrans dengan Laravel
                    </h2>
                    <div class="card border rounded shadow">
                        <div class="card-body">
                            <form id="donation-form">
                                <div class="row mb-3">
{{--                                    <div class="col-md-6 mb-2">--}}
{{--                                        <label for="name" class="form-label">Name</label>--}}
{{--                                        <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Your Name" required>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-6 mb-2">--}}
{{--                                        <label for="email" class="form-label">Email</label>--}}
{{--                                        <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Your Email">--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-12 mb-2">--}}
{{--                                        <label for="amount" class="form-label">Amount</label>--}}
{{--                                        <input type="number" id="amount" name="amount" value="{{ old('amount') }}" class="form-control @error('amount') is-invalid @enderror" required>--}}
{{--                                    </div>--}}
{{--                                    <div class="col-md-12">--}}
{{--                                        <label for="note" class="form-label">Note</label>--}}
{{--                                        <textarea name="note" id="note" cols="30" rows="5" class="form-control @error('note') is-invalid @enderror">{{ old('note') }}</textarea>--}}
{{--                                    </div>--}}
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-primary" id="pay-button">Pay</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('js')
    <script src="{{ asset('/js/helper.js') }}"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.clientKey') }}"></script>
    <script>
        var path = '/{{ request()->path() }}';
        function eventPay() {
            $('#pay-button').on('click', function (e) {
                e.preventDefault();
                payHandler();
            });
        }

        async function payHandler() {
            try {
                let response = await $.post(path);
                let snapToken = response['data']['snap_token'];
                snap.pay(snapToken, {
                    onSuccess: function (result) {
                        console.log(result)
                    },

                    onPending: function (result) {
                        console.log(result)
                    },

                    onError: function (result) {
                        console.log(result)
                    }
                })
                console.log(response)
            }catch (e) {
                console.log(e);
            }
        }

        $(document).ready(function () {
            eventPay();
        })
    </script>
@endsection
