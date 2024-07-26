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
                        @elseif($data->status === 1)
                            <div class="chip-status-info">barang di packing</div>
                        @elseif($data->status === 2)
                            <div class="chip-status-info">barang siap di ambil</div>
                        @elseif($data->status === 3)
                            <div class="chip-status-info">barang di kirim</div>
                        @elseif($data->status === 4)
                            <div class="chip-status-success">selesai</div>
                        @endif
                    </span>
                </div>
            </div>
            <hr class="custom-divider"/>
            <div class="d-flex w-100 gap-3">
                <div class="flex-grow-1 d-flex gap-2" style="flex-direction: column;">
                    @foreach($data->keranjang as $cart)
                        <div class="cart-item-container" style="height: fit-content;">
                            <img src="{{ $cart->product->gambar }}" alt="product-image">
                            <div class="flex-grow-1">
                                <p style="color: var(--dark); font-size: 1em; margin-bottom: 0; font-weight: bold">{{ $cart->product->nama }}</p>
                                <p style="margin-bottom: 0; color: var(--dark-tint); font-size: 0.8em;">{{ $cart->product->category->nama }}</p>
                                <div class="d-flex align-items-center" style="font-size: 0.8em;">
                                    <span style="color: var(--dark-tint);" class="me-1">Jumlah: </span>
                                    <span style="color: var(--dark); font-weight: bold;">{{ $cart->qty }}X (Rp.{{ number_format($cart->harga, 0, ',' ,'.') }})</span>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end" style="width: 150px;">
                                <p style="font-size: 1em; font-weight: bold; color: var(--dark);">
                                    Rp{{ number_format($cart->total, 0, ',' ,'.') }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="card-content" style="width: 350px; height: fit-content;">
                    <p style="font-size: 1em; font-weight: bold; color: var(--dark);">Ringkasan Belanja</p>
                    <hr class="custom-divider"/>
                    <div class="d-flex align-items-center justify-content-between mb-1" style="font-size: 1em;">
                        <span style="color: var(--dark-tint); font-size: 0.8em">Subtotal</span>
                        <span id="lbl-sub-total"
                              style="color: var(--dark); font-weight: 600;">Rp{{ number_format($data->sub_total, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-3" style="font-size: 1em;">
                        <span style="color: var(--dark-tint); font-size: 0.8em">Biaya Pengiriman</span>
                        <span id="lbl-shipment"
                              style="color: var(--dark); font-weight: 600;">Rp{{ number_format($data->ongkir, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex align-items-center justify-content-between mb-1" style="font-size: 1em;">
                        <span style="color: var(--dark-tint); font-size: 0.8em">Total</span>
                        <span id="lbl-total"
                              style="color: var(--dark); font-weight: bold;">Rp{{ number_format($data->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            @if($data->kredit)
                <hr class="custom-divider"/>
                <p style="font-size: 1em; font-weight: bold; color: var(--dark);">Data Angsuran</p>
                <div class="w-100 card-content">
                    <table id="table-data" class="display table w-100">
                        <thead>
                        <tr>
                            <th width="5%" class="text-center"></th>
                            <th width="12%" class="text-center">Tanggal</th>
                            <th>Angsuran</th>
                            <th width="12%" class="text-end">Total (Rp)</th>
                            <th width="10%" class="text-center">Status</th>
                            <th width="10%" class="text-center">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data->angsuran as $angsuran)
                            <tr>
                                <td class="middle-header text-center no-sort">{{ $loop->index + 1 }}</td>
                                <td class="middle-header text-center no-sort">
                                    @if($angsuran->tanggal !== null)
                                        <span>{{ $angsuran->tanggal }}</span>
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                                <td class="middle-header">
                                    Angsuran ke {{ $angsuran->index }}
                                </td>
                                <td class="middle-header text-end">{{ number_format($angsuran->total, 0, ',', '.') }}</td>
                                <td class="middle-header text-center">
                                    <div class="w-100 d-flex justify-content-center align-items-center gap-1">
                                        @if($angsuran->lunas)
                                            <div class="chip-status-success">Lunas</div>
                                        @else
                                            <div class="chip-status-danger">Belum Lunas</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="middle-header text-center">
                                    <div class="w-100 d-flex justify-content-center align-items-center gap-1">
                                        @if($angsuran->lunas)
                                            -
                                        @else
                                            <a href="#" data-id="{{ $angsuran->id }}"
                                               class="btn-action-primary btn-pay-instalment" style="padding: 5px 10px;">Bayar</a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('/js/helper.js') }}"></script>
    <script src="https://app.sandbox.midtrans.com/snap/snap.js"
            data-client-key="{{ config('services.midtrans.clientKey') }}"></script>

    <script>
        var path = '/{{ request()->path() }}';

        function generateTable() {
            $('#table-data').DataTable({
                "aaSorting": [],
                "order": [],
                scrollX: true,
                responsive: true,
                dom: 't',
                "fnDrawCallback": function (setting) {
                    // eventDelete();
                },
                columnDefs: [
                    {orderable: false, targets: [0, 1, 2, 3, 4]}
                ]
            });
        }

        function eventPayInstalment() {
            $('.btn-pay-instalment').on('click', function (e) {
                e.preventDefault();
                let id = this.dataset.id;
                payHandler(id);
            })
        }

        async function payHandler(id) {
            try {
                let url = path + '/angsuran/' + id;
                let response = await $.post(url);
                let snapToken = response['data']['snap_token'];
                snap.pay(snapToken, {
                    onSuccess: function (result) {
                        console.log(result);
                        let urlPay = url + '/pay';
                        paymentSuccessCallback(urlPay);
                    },

                    onPending: function (result) {
                        console.log(result)
                    },

                    onError: function (result) {
                        console.log(result);
                        ErrorAlert('Error', 'error midtrans payment');
                    }
                })
            } catch (e) {
                let error_message = JSON.parse(e.responseText);
                ErrorAlert('Error', error_message.message);
            }
        }

        async function paymentSuccessCallback(url) {
            try {
                blockLoading(true);
                let response = await $.post(url);
                blockLoading(false);
                Swal.fire({
                    title: 'Success',
                    text: 'Pembayaran Berhasil',
                    icon: 'success',
                    timer: 700
                }).then(() => {
                    window.location.reload();
                })
            } catch (e) {
                blockLoading(false);
                let error_message = JSON.parse(e.responseText);
                ErrorAlert('Error', error_message.message);
            }
        }

        $(document).ready(function () {
            generateTable();
            eventPayInstalment();
        });
    </script>
@endsection
