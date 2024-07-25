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
                        <td class="middle-header text-end">{{ number_format($cart->harga, 0, ',', '.') }}</td>
                        <td class="middle-header text-end">{{ number_format($cart->total, 0, ',', '.') }}</td>
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
        <div class="cart-action-container" style="width: 500px;">
            <p style="font-size: 1em; font-weight: bold; color: var(--dark);">Ringkasan Belanja</p>
            <hr class="custom-divider"/>
            <div class="w-100">
                <span class="input-label">Metode Pembelian</span>
                <div class="mt-2">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input buying-method" type="radio" name="buying-method"
                               id="cash"
                               value="cash" checked>
                        <label class="form-check-label" for="cash" style="font-size: 0.8em; color: var(--dark);">
                            Tunai
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input buying-method" type="radio" name="buying-method" id="credit"
                               value="credit">
                        <label class="form-check-label" for="credit" style="font-size: 0.8em; color: var(--dark);">
                            Kredit
                        </label>
                    </div>
                </div>
            </div>
            <div class="w-100 d-none" id="panel-buying-method">
                <hr class="custom-divider"/>
                <div class="w-100 mb-1">
                    <label for="interest" class="form-label input-label">Jangka Waktu</label>
                    <select id="interest" name="interest" class="text-input">
                        @foreach($interests as $interest)
                            <option value="{{ $interest->id }}"
                                    data-price="{{ $interest->bunga }}"
                                    data-count="{{ $interest->jangka_waktu }}">{{ $interest->jangka_waktu }} minggu
                                ({{ $interest->bunga }}%)
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
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
            <div class="d-flex align-items-center justify-content-between mb-1 d-none" id="panel-interest"
                 style="font-size: 1em;">
                <span style="color: var(--dark-tint); font-size: 0.8em">Bunga</span>
                <span id="lbl-interest"
                      style="color: var(--dark); font-weight: 600;">Rp0</span>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-3" style="font-size: 1em;">
                <span style="color: var(--dark-tint); font-size: 0.8em">Biaya Pengiriman</span>
                <span id="lbl-shipment"
                      style="color: var(--dark); font-weight: 600;">Rp{{ number_format($totalShipment, 0, ',', '.') }}</span>
            </div>
            <div class="w-100 mb-3 d-none" id="panel-dp">
                <hr class="custom-divider"/>
                <label for="dp" class="form-label input-label">DP</label>
                <input type="number" value="0" class="text-input"
                       id="dp"
                       name="dp">
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
        var strTotalInterest = '0';

        function generateTotal() {
            let intSubtotal = parseInt(strSubTotal);
            let intShipment = parseInt(strShipment);
            let intInterest = parseInt(strTotalInterest);
            let tmpTotalInterest = (intInterest / 100) * intSubtotal;
            let totalInterest = Math.round(tmpTotalInterest);
            let intDP = $('#dp').val() === '' ? 0 : parseInt($('#dp').val());
            let total = intSubtotal + intShipment + totalInterest - intDP;
            $('#lbl-total').html('Rp.' + total.toLocaleString('id-ID'));
        }

        function eventChangeShipment() {
            $('#shipment').on('change', function (e) {
                strShipment = $(this).find('option:selected').attr('data-price');
                $('#lbl-shipment').html('Rp.' + parseInt(strShipment).toLocaleString('id-ID'));
                generateTotal();
            })
        }

        function eventChangeInterest() {
            $('#interest').on('change', function (e) {
                strTotalInterest = $(this).find('option:selected').attr('data-price');
                let intSubtotal = parseInt(strSubTotal);
                let intInterest = parseInt(strTotalInterest);
                let tmpTotalInterest = (intInterest / 100) * intSubtotal;
                let totalInterest = Math.round(tmpTotalInterest);
                $('#lbl-interest').html('Rp.' + totalInterest.toLocaleString('id-ID'));
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

        function eventChangeBuyingMethod() {
            $('.buying-method').on('change', function () {
                changeBuyingMethodHandler();
            })
        }

        function changeBuyingMethodHandler() {
            let val = $('input[name=buying-method]:checked').val();
            let elPanelInterest = $('#panel-interest');
            let elPanelBuyingMethod = $('#panel-buying-method');
            let elPanelDP = $('#panel-dp');
            if (val === 'cash') {
                elPanelInterest.addClass('d-none');
                elPanelBuyingMethod.addClass('d-none');
                elPanelBuyingMethod.addClass('d-none');
                strTotalInterest = '0';
                $('#dp').val(0)
                $('#lbl-interest').html('Rp.0');
                generateTotal();
            } else {
                elPanelInterest.removeClass('d-none');
                elPanelBuyingMethod.removeClass('d-none');
                elPanelDP.removeClass('d-none');
                strTotalInterest = $('#interest').find('option:selected').attr('data-price');
                let intSubtotal = parseInt(strSubTotal);
                let intInterest = parseInt(strTotalInterest);
                let tmpTotalInterest = (intInterest / 100) * intSubtotal;
                let totalInterest = Math.round(tmpTotalInterest);
                $('#dp').val(0)
                $('#lbl-interest').html('Rp.' + totalInterest.toLocaleString('id-ID'));
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
                let buyingMethod = $('input[name=buying-method]:checked').val();
                let dp = $('#dp').val();

                let intSubtotal = parseInt(strSubTotal);
                let intInterest = parseInt(strTotalInterest);
                let tmpTotalInterest = (intInterest / 100) * intSubtotal;
                let totalInterest = Math.round(tmpTotalInterest);

                let countInterest = $('#interest').find('option:selected').attr('data-count');
                blockLoading(true);
                let response = await $.post(url, {
                    shipping_method: shippingMethod,
                    destination: destination,
                    address: address,
                    buying_method: buyingMethod,
                    interest: totalInterest,
                    dp: dp,
                    count_interest: countInterest,
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

        async function eventTypeDP() {
            $("#dp").keyup(
                debounce(function (e) {
                    generateTotal();
                }, 500)
            );
        }

        $(document).ready(function () {
            eventChangeShippingMethod();
            eventChangeBuyingMethod();
            generateTotal();
            eventChangeShipment();
            eventChangeInterest();
            eventDeleteCart();
            eventCheckout();
            generateTable();
            eventTypeDP();
        })
    </script>
@endsection
