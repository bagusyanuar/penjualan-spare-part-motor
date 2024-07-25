@extends('customer.layout')

@section('content')
    @if (\Illuminate\Support\Facades\Session::has('failed'))
        <script>
            Swal.fire("Ooops", '{{ \Illuminate\Support\Facades\Session::get('failed') }}', "error")
        </script>
    @endif
    @if (\Illuminate\Support\Facades\Session::has('success'))
        <script>
            Swal.fire({
                title: 'Success',
                text: '{{ \Illuminate\Support\Facades\Session::get('success') }}',
                icon: 'success',
                timer: 700
            }).then(() => {
                window.location.reload();
            })
        </script>
    @endif
    <div class="lazy-backdrop" id="overlay-loading">
        <div class="d-flex flex-column justify-content-center align-items-center">
            <div class="spinner-border text-light" role="status">
            </div>
            <p class="text-light">Sedang Menyimpan Data...</p>
        </div>
    </div>
    <div class="w-100 d-flex justify-content-between align-items-center mb-3">
        <p class="page-title">Pesanan</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('customer.home') }}">Home</a></li>
                <li class="breadcrumb-item"><a href="{{ route('customer.order') }}">Pesanan</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $data->no_penjualan }}</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex">
        <div class="categories-sidebar">
            <a href="{{ route('customer.account') }}" class="categories-link">Akun Saya</a>
            <a href="{{ route('customer.order') }}" class="categories-link active">Pesanan</a>
            <a href="{{ route('customer.logout') }}" class="categories-link">Logout</a>
        </div>
        <div class="flex-grow-1" style="padding-left: 25px">
            <div class="mb-3" style="font-size: 0.8em; color: var(--dark);">
                <div class="d-flex align-items-center mb-1">
                    <span style="" class="me-2">No. Pembelian :</span>
                    <span style="font-weight: 600;">{{ $data->no_penjualan }}</span>
                </div>
                <div class="d-flex align-items-center mb-1">
                    <span style="" class="me-2">Tgl. Pembelian :</span>
                    <span style="font-weight: 600;">{{ \Carbon\Carbon::parse($data->tanggal)->format('d F Y') }}</span>
                </div>
                <div class="d-flex align-items-center mb-1">
                    <span style="" class="me-2">Metode Pengiriman :</span>
                    @if($data->is_kirim)
                        <div class="d-flex align-items-center gap-1">
                            <div class="delivery-status-container"><i class="bx bx-car"></i></div>
                            <span style="font-weight: 600;">Di Kirim</span>
                        </div>
                    @else
                        <span style="font-weight: 600;">Ambil Sendiri</span>
                    @endif
                </div>
                @if($data->is_kirim)
                    <div class="d-flex align-items-center mb-1">
                        <span style="" class="me-2">Alamat Pengiriman :</span>
                        <span style="font-weight: 600;">{{ $data->alamat }} ({{ $data->kota }})</span>
                    </div>
                @endif
                <div class="d-flex align-items-center mb-1">
                    <span style="" class="me-2">Status :</span>
                    <span style="font-weight: 600;">
                        @if($data->status === 0)
                            <div class="chip-status-danger">menunggu pembayaran</div>
                        @endif
                    </span>
                </div>
            </div>
            <hr class="custom-divider"/>
            <div class="d-flex w-100 gap-3">
                <div class="flex-grow-1 d-flex gap-2">
                    <div class="w-100 d-flex justify-content-center align-items-center">
                        <img src="{{ asset('/assets/images/payment-bg.png') }}" alt="payment-image">
                    </div>
                </div>
                <div class="card-content" style="width: 400px; height: fit-content;">
                    <p style="font-size: 1em; font-weight: bold; color: var(--dark);">Pembayaran</p>
                    <hr class="custom-divider"/>
                    <div class="d-flex align-items-center justify-content-between mb-1" style="font-size: 1em;">
                        <span style="color: var(--dark); font-size: 0.8em">Total</span>
                        <span id="lbl-total"
                              style="color: var(--dark); font-weight: bold;">Rp{{ number_format($data->total, 0, ',', '.') }}</span>
                    </div>
                    <hr class="custom-divider"/>
                    {{--                    <form method="post" id="form-data">--}}
                    {{--                        @csrf--}}
                    {{--                        <div class="w-100 mb-2">--}}
                    {{--                            <label for="bank" class="form-label input-label">Bank</label>--}}
                    {{--                            <select id="bank" name="bank" class="text-input">--}}
                    {{--                                <option value="BRI">BRI (91283948124)</option>--}}
                    {{--                                <option value="BCA">BCA (99829948499)</option>--}}
                    {{--                                <option value="MANDIRI">MANDIRI (12984912885)</option>--}}
                    {{--                            </select>--}}
                    {{--                        </div>--}}
                    {{--                        <div class="w-100 mb-2">--}}
                    {{--                            <label for="name" class="form-label input-label">Atas Nama</label>--}}
                    {{--                            <input type="text" placeholder="atas nama" class="text-input" id="name"--}}
                    {{--                                   name="name">--}}
                    {{--                        </div>--}}
                    {{--                        <div class="w-100">--}}
                    {{--                            <label for="document-dropzone" class="form-label input-label">Bukti Transfer</label>--}}
                    {{--                            <div class="w-100 needsclick dropzone mb-3" id="document-dropzone"></div>--}}
                    {{--                        </div>--}}
                    {{--                    </form>--}}
                    {{--                    <hr class="custom-divider"/>--}}
                    <a href="#" class="btn-action-primary" id="btn-save">Bayar</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link href="{{ asset('/css/dropzone.min.css') }}" rel="stylesheet"/>
@endsection

@section('js')
    <script src="{{ asset('/js/helper.js') }}"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.clientKey') }}"></script>
    <script src="{{ asset('/js/dropzone.min.js') }}"></script>
    <script>
        var path = '/{{ request()->path() }}';
        var snapToken = '{{ $snapToken }}';
        function eventPay() {
            $('#btn-save').on('click', function (e) {
                e.preventDefault();
                payHandler();
            });
        }

        function payHandler() {
            snap.pay(snapToken, {
                onSuccess: function (result) {
                    console.log(result)
                    paymentSuccessCallback(snapToken);
                },

                onPending: function (result) {
                    console.log(result)
                },

                onError: function (result) {
                    ErrorAlert('Error', 'error midtrans payment');
                }
            })

        }

        async function paymentSuccessCallback(snapToken) {
            try {
                blockLoading(true);
                let response = await $.post(path, {
                    snap_token: snapToken,
                });
                blockLoading(false);
                Swal.fire({
                    title: 'Success',
                    text: 'Pembayaran Berhasil',
                    icon: 'success',
                    timer: 700
                }).then(() => {
                    window.location.href = '/pesanan';
                })
            } catch (e) {
                blockLoading(false);
                let error_message = JSON.parse(e.responseText);
                ErrorAlert('Error', error_message.message);
            }
        }

        $(document).ready(function () {
            eventPay();
        })
    </script>
@endsection
