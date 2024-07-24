@extends('customer.layout')

@section('css')
    <style>
        .btn-table-action-delete {
            width: 25px;
            height: 25px;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: var(--danger);
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }

        .btn-table-action-delete:hover {
            color: white;
            text-decoration: none;
        }
    </style>
@endsection

@section('content')
    <div class="lazy-backdrop" id="overlay-loading">
        <div class="d-flex flex-column justify-content-center align-items-center">
            <div class="spinner-border text-light" role="status">
            </div>
            <p class="text-light">Sedang Menyimpan Data...</p>
        </div>
    </div>
    <div class="w-100 d-flex justify-content-between align-items-center mb-1">
        <p class="page-title">Keranjang</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('customer.home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Keranjang</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex" style="gap: 1rem">
        <div class="flex-grow-1 card-content" style="height: fit-content">
            <table id="table-data" class="display table w-100">
                <thead>
                <tr>
                    <th width="5%" class="text-center"></th>
                    <th width="12%" class="text-center middle-header">Gambar</th>
                    <th>Nama</th>
                    <th width="12%" class="text-center">Qty</th>
                    <th width="12%" class="text-end">Harga (Rp)</th>
                    <th width="12%" class="text-end">Total (Rp)</th>
                    <th width="10%" class="text-center"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($carts as $cart)
                    <tr>
                        <td class="middle-header text-center no-sort">{{ $loop->index + 1 }}</td>
                        <td class="middle-header text-center">
                            <div class="w-100 d-flex justify-content-center">
                                <a href="{{ $cart->product->gambar }}" target="_blank" class="box-product-image">
                                    <img src="{{ $cart->product->gambar }}" alt="product-image">
                                </a>
                            </div>
                        </td>
                        <td class="middle-header">{{ $cart->product->nama }}</td>
                        <td class="middle-header text-center">{{ $cart->qty }}</td>
                        <td class="middle-header text-center">{{ $cart->harga }}</td>
                        <td class="middle-header text-center">{{ $cart->total }}</td>
                        <td class="middle-header text-center">
                            <div class="w-100 d-flex justify-content-center align-items-center gap-1">
                                <a href="#" class="btn-table-action-delete" data-id="{{ $cart->id }}"><i
                                        class="bx bx-trash"></i></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{--        <div class="cart-list-container">--}}
        {{--            --}}{{--            <p style="font-size: 0.8em; font-weight: bold; color: var(--dark);">Daftar Belanja</p>--}}
        {{--            @forelse($carts as $cart)--}}
        {{--                <div class="cart-item-container mb-3">--}}
        {{--                    <img src="{{ $cart->product->gambar }}" alt="product-image">--}}
        {{--                    <div class="flex-grow-1">--}}
        {{--                        <p style="color: var(--dark); font-size: 1em; margin-bottom: 0; font-weight: bold">{{ $cart->product->nama }}</p>--}}
        {{--                        <p style="margin-bottom: 0; color: var(--dark-tint); font-size: 0.8em;">{{ $cart->product->category->nama }}</p>--}}
        {{--                        <div class="d-flex align-items-center" style="font-size: 0.8em;">--}}
        {{--                            <span style="color: var(--dark-tint);" class="me-1">Jumlah: </span>--}}
        {{--                            <span style="color: var(--dark); font-weight: bold;">{{ $cart->qty }}X (Rp.{{ number_format($cart->harga, 0, ',' ,'.') }})</span>--}}
        {{--                        </div>--}}
        {{--                        <div class="d-flex justify-content-start w-100">--}}
        {{--                            <a href="#" class="btn-delete-item" data-id="{{ $cart->id }}">--}}
        {{--                                <i class='bx bx-trash'></i>--}}
        {{--                            </a>--}}
        {{--                        </div>--}}
        {{--                    </div>--}}
        {{--                    <div class="d-flex justify-content-end" style="width: 150px;">--}}
        {{--                        <p style="font-size: 1em; font-weight: bold; color: var(--dark);">--}}
        {{--                            Rp{{ number_format($cart->total, 0, ',' ,'.') }}</p>--}}
        {{--                    </div>--}}
        {{--                </div>--}}
        {{--            @empty--}}
        {{--                <div class="w-100 d-flex justify-content-center align-items-center flex-column"--}}
        {{--                     style="background-color: white; border-radius: 12px; box-shadow: 0 8px 10px rgba(0, 0, 0, 0.2); padding: 1rem 1.5rem; min-height: 495px; ">--}}
        {{--                    <p style="margin-bottom: 1rem; font-weight: bold;">Belum Ada Data Belanja...</p>--}}
        {{--                    <a href="{{ route('customer.product') }}" class="btn-action-primary" style="width: fit-content">Pergi--}}
        {{--                        Belanja</a>--}}
        {{--                </div>--}}
        {{--            @endforelse--}}
        {{--        </div>--}}
        <div class="cart-action-container">
            <p style="font-size: 1em; font-weight: bold; color: var(--dark);">Ringkasan Belanja</p>
            <hr class="custom-divider"/>
            <div class="w-100">
                <span class="input-label">Metode Pengiriman</span>
                <div class="mt-2">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input shipping-method" type="radio" name="shipping-method"
                               id="delivery"
                               value="delivery" checked>
                        <label class="form-check-label" for="delivery" style="font-size: 0.8em; color: var(--dark);">
                            Kirim
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input shipping-method" type="radio" name="shipping-method" id="pickup"
                               value="pickup">
                        <label class="form-check-label" for="pickup" style="font-size: 0.8em; color: var(--dark);">
                            Ambil Sendiri
                        </label>
                    </div>
                </div>
                <hr class="custom-divider"/>
            </div>
            <div class="w-100" id="panel-shipping">
                <div class="w-100 mb-1">
                    <label for="shipment" class="form-label input-label">Tujuan</label>
                    <select id="shipment" name="shipment" class="text-input">
                        @foreach($shipments as $shipment)
                            <option value="{{ $shipment->id }}"
                                    data-price="{{ $shipment->harga }}">{{ $shipment->kota }}
                                (Rp{{ number_format($shipment->harga, 0, ',', '.') }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="w-100 mb-1">
                    <label for="address" class="form-label input-label">Alamat</label>
                    <textarea rows="3" placeholder="contoh: Wonosaren rt 04 rw 08, jagalan, jebres" class="text-input"
                              id="address"
                              name="address">{{ $address }}</textarea>
                </div>
                <hr class="custom-divider"/>
            </div>

            <div class="d-flex align-items-center justify-content-between mb-1" style="font-size: 1em;">
                <span style="color: var(--dark-tint); font-size: 0.8em">Subtotal</span>
                <span id="lbl-sub-total"
                      style="color: var(--dark); font-weight: 600;">Rp{{ number_format($subTotal, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-3" style="font-size: 1em;">
                <span style="color: var(--dark-tint); font-size: 0.8em">Biaya Pengiriman</span>
                <span id="lbl-shipment"
                      style="color: var(--dark); font-weight: 600;">Rp{{ number_format($totalShipment, 0, ',', '.') }}</span>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-1" style="font-size: 1em;">
                <span style="color: var(--dark-tint); font-size: 0.8em">Total</span>
                <span id="lbl-total"
                      style="color: var(--dark); font-weight: bold;">Rp{{ number_format(($subTotal + $totalShipment), 0, ',', '.') }}</span>
            </div>
            <hr class="custom-divider"/>
            <a href="#" class="btn-action-primary mb-1" id="btn-checkout">Beli</a>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('/js/helper.js') }}"></script>
    <script>
        var path = '/{{request()->path()}}';
        var strSubTotal = '{{ $subTotal }}';
        var strShipment = '{{ $totalShipment }}';

        function generateTotal() {
            let intSubtotal = parseInt(strSubTotal);
            let intShipment = parseInt(strShipment);
            let total = intSubtotal + intShipment;
            $('#lbl-total').html('Rp.' + total.toLocaleString('id-ID'));
        }

        function eventChangeShipment() {
            $('#shipment').on('change', function (e) {
                strShipment = $(this).find('option:selected').attr('data-price');
                $('#lbl-shipment').html('Rp.' + parseInt(strShipment).toLocaleString('id-ID'));
                generateTotal();
            })
        }

        function eventDeleteCart() {
            $('.btn-delete-item').on('click', function (e) {
                e.preventDefault();
                let id = this.dataset.id;
                AlertConfirm('Konfirmasi', 'Apakah anda yakin ingin menghapus data?', function () {
                    let url = path + '/' + id + '/delete';
                    BaseDeleteHandler(url, id);
                })
            })
        }

        function eventChangeShippingMethod() {
            $('.shipping-method').on('change', function () {
                changeShippingMethodHandler();
            })
        }

        function changeShippingMethodHandler() {
            let val = $('input[name=shipping-method]:checked').val();
            let elPanelShipping = $('#panel-shipping');
            if (val === 'pickup') {
                elPanelShipping.addClass('d-none');
                strShipment = '0';
                $('#lbl-shipment').html('Rp.0');
                generateTotal();
            } else {
                elPanelShipping.removeClass('d-none');
                strShipment = $('#shipment').find('option:selected').attr('data-price');
                $('#lbl-shipment').html('Rp.' + parseInt(strShipment).toLocaleString('id-ID'));
                generateTotal();
            }
        }

        function eventCheckout() {
            $('#btn-checkout').on('click', function (e) {
                e.preventDefault();
                let id = this.dataset.id;
                checkoutHandler(id)
            })
        }

        async function checkoutHandler(id) {
            try {
                let url = path + '/checkout';
                let shippingMethod = $('input[name=shipping-method]:checked').val();
                let destination = $('#shipment').val();
                let address = $('#address').val();
                blockLoading(true);
                let response = await $.post(url, {
                    shipping_method: shippingMethod,
                    destination: destination,
                    address: address
                });
                let id = response['data'];
                blockLoading(false);
                Swal.fire({
                    title: 'Success',
                    text: 'Berhasil membeli product...',
                    icon: 'success',
                    timer: 700
                }).then(() => {
                    window.location.href = '/pesanan/' + id + '/pembayaran';
                })
            } catch (e) {
                blockLoading(false);
                let error_message = JSON.parse(e.responseText);
                ErrorAlert('Error', error_message.message);
            }
        }

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
                    {orderable: false, targets: [0, 1, 2, 3, 4, 5, 6]}
                ]
            });
        }

        $(document).ready(function () {
            eventChangeShippingMethod();
            generateTotal();
            eventChangeShipment();
            eventDeleteCart();
            eventCheckout();
            generateTable();
        })
    </script>
@endsection
