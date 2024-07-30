@extends('admin.layout')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-1">
        <div>
            <p class="content-title">Penjualan</p>
            <p class="content-sub-title">Manajemen data penjualan baru</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.penjualan') }}">Pesanan</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $data->no_penjualan }}</li>
            </ol>
        </nav>
    </div>
    <div class="card-content">
        <div class="content-header mb-3">
            <p class="header-title" style="font-size: 0.8em">Data Pesanan</p>
        </div>
        <hr class="custom-divider"/>
        <div class="row w-100">
            <div class="col-8">
                <div class="w-100 d-flex align-items-center mb-1"
                     style="font-size: 0.8em; font-weight: 600; color: var(--dark);">
                    <p style="margin-bottom: 0; font-weight: 500;" class="me-2">No. Pesanan :</p>
                    <p style="margin-bottom: 0">{{ $data->no_penjualan }}</p>
                </div>
                <div class="w-100 d-flex align-items-center mb-1"
                     style="font-size: 0.8em; font-weight: 600; color: var(--dark);">
                    <p style="margin-bottom: 0; font-weight: 500;" class="me-2">Tanggal Penjualan :</p>
                    <p style="margin-bottom: 0">{{ \Carbon\Carbon::parse($data->tanggal)->format('d F Y') }}</p>
                </div>
                <div class="w-100 d-flex align-items-center mb-1"
                     style="font-size: 0.8em; font-weight: 600; color: var(--dark);">
                    <p style="margin-bottom: 0; font-weight: 500;" class="me-2">Kredit :</p>
                    <div style="margin-bottom: 0" class="d-flex gap-1">
                        @if($data->kredit)
                            <div class="d-flex justify-content-center align-items-center"
                                 style="color: white; height: 22px; width: 22px; background-color: var(--success); border-radius: 4px;">
                                <i class="bx bx-check"></i>
                            </div>
                            <div class="ms-2">({{ $data->jumlah_angsuran }})X angsuran</div>
                        @else
                            <div class="d-flex justify-content-center align-items-center"
                                 style="color: white; height: 22px; width: 22px; background-color: var(--danger); border-radius: 4px;">
                                <i class="bx bx-x"></i>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="w-100 d-flex align-items-center mb-1"
                     style="font-size: 0.8em; font-weight: 600; color: var(--dark);">
                    <p style="margin-bottom: 0; font-weight: 500;" class="me-2">Metode Pengiriman :</p>
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
                    <div class="w-100 d-flex align-items-center"
                         style="font-size: 0.8em; font-weight: 600; color: var(--dark);">
                        <p style="margin-bottom: 0; font-weight: 500;" class="me-2">Alamat Pengiriman :</p>
                        <p style="margin-bottom: 0">{{ $data->alamat }} ({{ $data->kota }})</p>
                    </div>
                @endif

            </div>
            <div class="col-4"></div>
        </div>
        <hr class="custom-divider"/>
        <table id="table-data-cart" class="display table w-100">
            <thead>
            <tr>
                <th width="5%" class="text-center">#</th>
                <th width="12%" class="text-center middle-header">Gambar</th>
                <th>Nama Product</th>
                <th width="10%" class="text-center">Qty</th>
                <th width="10%" class="text-end">Harga</th>
                <th width="10%" class="text-end">Total</th>
            </tr>
            </thead>
        </table>
        <hr class="custom-divider"/>
        <div class="w-100 d-flex justify-content-end mb-1"
             style="font-size: 0.8em; font-weight: bold; color: var(--dark);">
            <div class="me-2 w-100 text-end" style="width: 80%">Sub Total :</div>
            <div class="text-end" style="width: 20%">Rp.{{ number_format($data->sub_total, 0, ',', '.') }}</div>
        </div>
        <div class="w-100 d-flex justify-content-end mb-1"
             style="font-size: 0.8em; font-weight: bold; color: var(--dark);">
            <div class="me-2 w-100 text-end" style="width: 80%">Ongkir :</div>
            <div class="text-end" style="width: 20%">Rp.{{ number_format($data->ongkir, 0, ',', '.') }}</div>
        </div>
        <div class="w-100 d-flex justify-content-end mb-1" style="font-size: 0.8em; font-weight: bold; color: var(--dark);">
            <div class="me-2 w-100 text-end" style="width: 80%">Total :</div>
            <div class="text-end" style="width: 20%">Rp.{{ number_format($data->total, 0, ',', '.') }}</div>
        </div>
        <div class="w-100 d-flex justify-content-end mb-1" style="font-size: 0.8em; font-weight: bold; color: var(--dark);">
            <div class="me-2 w-100 text-end" style="width: 80%">DP :</div>
            <div class="text-end" style="width: 20%">Rp.{{ number_format($data->dp, 0, ',', '.') }}</div>
        </div>

    </div>
@endsection
@section('js')
    <script src="{{ asset('/js/helper.js') }}"></script>
    <script>
        var path = '/{{ request()->path() }}';
        var table;

        function generateTableKeranjang() {
            table = $('#table-data-cart').DataTable({
                ajax: {
                    type: 'GET',
                    url: path,
                    'data': function (d) {
                        d.status = 1
                    }
                },
                "aaSorting": [],
                "order": [],
                scrollX: true,
                responsive: true,
                paging: true,
                "fnDrawCallback": function (setting) {
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        searchable: false,
                        orderable: false,
                        className: 'text-center middle-header',
                    },
                    {
                        data: 'product.gambar',
                        orderable: false,
                        className: 'middle-header text-center',
                        render: function (data) {
                            if (data !== null) {
                                return '<div class="w-100 d-flex justify-content-center">' +
                                    '<a href="' + data + '" target="_blank" class="box-product-image">' +
                                    '<img src="' + data + '" alt="product-image" />' +
                                    '</a>' +
                                    '</div>';
                            }
                            return '-';
                        }
                    },
                    {
                        data: 'product.nama',
                        className: 'middle-header',
                    },
                    {
                        data: 'qty',
                        className: 'middle-header text-center',
                        render: function (data) {
                            return data.toLocaleString('id-ID');
                        }
                    },

                    {
                        data: 'harga',
                        className: 'middle-header text-end',
                        render: function (data) {
                            return data.toLocaleString('id-ID');
                        }
                    },
                    {
                        data: 'total',
                        className: 'middle-header text-end',
                        render: function (data) {
                            return data.toLocaleString('id-ID');
                        }
                    },
                ],
            });
        }

        function eventSaveConfirmation() {
            $('#btn-confirm').on('click', function (e) {
                e.preventDefault();
                AlertConfirm('Konfirmasi', 'Apakah anda yakin ingin melakukan konfirmasi?', function () {
                    saveConfirmationHandler();
                })
            })
        }

        async function saveConfirmationHandler() {
            try {
                blockLoading(true);
                let status = $('#status').val();
                await $.post(path, {status});
                blockLoading(false);
                Swal.fire({
                    title: 'Success',
                    text: 'Berhasil melakukan konfirmasi data...',
                    icon: 'success',
                    timer: 700
                }).then(() => {
                    window.location.href = '/admin/penjualan';
                })
            } catch (e) {
                blockLoading(false);
                let error_message = JSON.parse(e.responseText);
                ErrorAlert('Error', error_message.message);
            }
        }

        $(document).ready(function () {
            generateTableKeranjang();
        })
    </script>
@endsection
