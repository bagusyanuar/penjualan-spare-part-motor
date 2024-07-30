@extends('admin.layout')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-1">
        <div>
            <p class="content-title">Penjualan</p>
            <p class="content-sub-title">Manajemen data penjualan</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Penjualan</li>
            </ol>
        </nav>
    </div>

    <ul class="nav nav-pills mb-3 custom-tab-pills" id="transaction-tab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link custom-tab-link active" id="pills-new-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-new"
                    type="button" role="tab" aria-controls="pills-new" aria-selected="true">
                Pesanan Baru
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link custom-tab-link" id="pills-packing-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-packing"
                    type="button" role="tab" aria-controls="pills-packing" aria-selected="false">
                Pesanan Selesai Packing
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link custom-tab-link" id="pills-finish-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-finish"
                    type="button" role="tab" aria-controls="pills-finish" aria-selected="false">
                Pesanan Selesai
            </button>
        </li>
    </ul>
    <div class="tab-content" id="transaction-content">
        <div class="tab-pane fade show active" id="pills-new" role="tabpanel" aria-labelledby="pills-new-tab">
            <div class="card-content">
                <div class="content-header mb-3">
                    <p class="header-title">Data Pesanan Baru</p>
                </div>
                <hr class="custom-divider"/>
                <table id="table-data-new-order" class="display table w-100">
                    <thead>
                    <tr>
                        <th width="5%" class="text-center">#</th>
                        <th>No. Penjualan</th>
                        <th width="10%" class="text-end">Sub Total</th>
                        <th width="10%" class="text-end">Ongkir</th>
                        <th width="10%" class="text-end">Total</th>
                        <th width="8%" class="text-center">Di Kirim</th>
                        <th width="8%" class="text-end">Kredit</th>
                        <th width="8%" class="text-center"></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-packing" role="tabpanel" aria-labelledby="pills-packing-tab">
            <div class="card-content">
                <div class="content-header mb-3">
                    <p class="header-title">Data Pesanan Selesai Di Packing</p>
                </div>
                <hr class="custom-divider"/>
                <table id="table-data-packing-order" class="display table w-100">
                    <thead>
                    <tr>
                        <th width="5%" class="text-center">#</th>
                        <th width="20%" class="text-center">No. Penjualan</th>
                        <th width="8%" class="text-center">Di Kirim</th>
                        <th width="15%" class="text-center">Status</th>
                        <th>Alamat</th>
                        <th width="8%" class="text-center"></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
        <div class="tab-pane fade" id="pills-finish" role="tabpanel" aria-labelledby="pills-finish-tab">
            <div class="card-content">
                <div class="content-header mb-3">
                    <p class="header-title">Data Pesanan Di Proses</p>
                </div>
                <hr class="custom-divider"/>
                <table id="table-data-finish-order" class="display table w-100">
                    <thead>
                    <tr>
                        <th width="5%" class="text-center">#</th>
                        <th>No. Penjualan</th>
                        <th width="10%" class="text-end">Sub Total</th>
                        <th width="10%" class="text-end">Ongkir</th>
                        <th width="10%" class="text-end">Total</th>
                        <th width="8%" class="text-center">Di Kirim</th>
                        <th width="8%" class="text-end">Kredit</th>
                        <th width="8%" class="text-center"></th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('/js/helper.js') }}"></script>
    <script>
        var path = '/{{ request()->path() }}';
        var table, tablePacking, tableFinish;

        function generateTableNewOrder() {
            table = $('#table-data-new-order').DataTable({
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
                        data: 'no_penjualan',
                        className: 'middle-header',
                    },
                    {
                        data: 'sub_total',
                        className: 'middle-header text-end',
                        render: function (data) {
                            return data.toLocaleString('id-ID');
                        }
                    },

                    {
                        data: 'ongkir',
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
                    {
                        data: 'is_kirim',
                        orderable: false,
                        className: 'text-center middle-header',
                        render: function (data) {
                            let id = data['id'];
                            if (data) {
                                return '<div class="w-100 d-flex justify-content-center align-items-center gap-1">' +
                                    '<div class="d-flex justify-content-center align-items-center"' +
                                    ' style="color: white; height: 22px; width: 22px; background-color: var(--success); border-radius: 4px;" data-id="' + id + '">' +
                                    '<i class="bx bx-check"></i>' +
                                    '</div>' +
                                    '</div>';
                            }
                            return '<div class="w-100 d-flex justify-content-center align-items-center gap-1">' +
                                '<div class="d-flex justify-content-center align-items-center"' +
                                ' style="color: white; height: 22px; width: 22px; background-color: var(--danger); border-radius: 4px;" data-id="' + id + '">' +
                                '<i class="bx bx-x"></i>' +
                                '</div>' +
                                '</div>';
                        }
                    },
                    {
                        data: 'kredit',
                        orderable: false,
                        className: 'text-center middle-header',
                        render: function (data) {
                            let id = data['id'];
                            if (data) {
                                return '<div class="w-100 d-flex justify-content-center align-items-center gap-1">' +
                                    '<div class="d-flex justify-content-center align-items-center"' +
                                    ' style="color: white; height: 22px; width: 22px; background-color: var(--success); border-radius: 4px;" data-id="' + id + '">' +
                                    '<i class="bx bx-check"></i>' +
                                    '</div>' +
                                    '</div>';
                            }
                            return '<div class="w-100 d-flex justify-content-center align-items-center gap-1">' +
                                '<div class="d-flex justify-content-center align-items-center"' +
                                ' style="color: white; height: 22px; width: 22px; background-color: var(--danger); border-radius: 4px;" data-id="' + id + '">' +
                                '<i class="bx bx-x"></i>' +
                                '</div>' +
                                '</div>';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        className: 'text-center middle-header',
                        render: function (data) {
                            let id = data['id'];
                            let urlDetail = path + '/' + id + '/pesanan-baru';
                            return '<div class="w-100 d-flex justify-content-center align-items-center gap-1">' +
                                '<a style="color: var(--dark-tint)" href="' + urlDetail + '" class="btn-table-action" data-id="' + id + '"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                                '</div>';
                        }
                    }
                ],
            });
        }

        function generateTablePackingOrder() {
            tablePacking = $('#table-data-packing-order').DataTable({
                ajax: {
                    type: 'GET',
                    url: path,
                    'data': function (d) {
                        d.status = 2
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
                        data: 'no_penjualan',
                        className: 'middle-header',
                    },
                    {
                        data: 'is_kirim',
                        orderable: false,
                        className: 'text-center middle-header',
                        render: function (data) {
                            let id = data['id'];
                            if (data) {
                                return '<div class="w-100 d-flex justify-content-center align-items-center gap-1">' +
                                    '<div class="d-flex justify-content-center align-items-center"' +
                                    ' style="color: white; height: 22px; width: 22px; background-color: var(--success); border-radius: 4px;" data-id="' + id + '">' +
                                    '<i class="bx bx-check"></i>' +
                                    '</div>' +
                                    '</div>';
                            }
                            return '<div class="w-100 d-flex justify-content-center align-items-center gap-1">' +
                                '<div class="d-flex justify-content-center align-items-center"' +
                                ' style="color: white; height: 22px; width: 22px; background-color: var(--danger); border-radius: 4px;" data-id="' + id + '">' +
                                '<i class="bx bx-x"></i>' +
                                '</div>' +
                                '</div>';
                        }
                    },
                    {
                        data: 'status',
                        orderable: false,
                        className: 'middle-header text-center',
                        render: function (data) {
                            let status = '-';
                            switch (data) {
                                case  2:
                                    status = '<div class="chip-status-info">barang siap di ambil</div>';
                                    break;
                                case  3:
                                    status = '<div class="chip-status-info">barang di kirim</div>';
                                    break;
                                default:
                                    break;
                            }
                            return status;
                        }
                    },
                    {
                        data: 'alamat',
                        className: 'middle-header',
                    },
                    {
                        data: null,
                        orderable: false,
                        className: 'text-center middle-header',
                        render: function (data) {
                            let id = data['id'];
                            let urlDetail = path + '/' + id + '/selesai-packing';
                            return '<div class="w-100 d-flex justify-content-center align-items-center gap-1">' +
                                '<a style="color: var(--dark-tint)" href="' + urlDetail + '" class="btn-table-action" data-id="' + id + '"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                                '</div>';
                        }
                    }
                ],
            });
        }

        function generateTableFinishOrder() {
            tableFinish = $('#table-data-finish-order').DataTable({
                ajax: {
                    type: 'GET',
                    url: path,
                    'data': function (d) {
                        d.status = 3
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
                        data: 'no_penjualan',
                        className: 'middle-header',
                    },
                    {
                        data: 'sub_total',
                        className: 'middle-header text-end',
                        render: function (data) {
                            return data.toLocaleString('id-ID');
                        }
                    },

                    {
                        data: 'ongkir',
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
                    {
                        data: 'is_kirim',
                        orderable: false,
                        className: 'text-center middle-header',
                        render: function (data) {
                            let id = data['id'];
                            if (data) {
                                return '<div class="w-100 d-flex justify-content-center align-items-center gap-1">' +
                                    '<div class="d-flex justify-content-center align-items-center"' +
                                    ' style="color: white; height: 22px; width: 22px; background-color: var(--success); border-radius: 4px;" data-id="' + id + '">' +
                                    '<i class="bx bx-check"></i>' +
                                    '</div>' +
                                    '</div>';
                            }
                            return '<div class="w-100 d-flex justify-content-center align-items-center gap-1">' +
                                '<div class="d-flex justify-content-center align-items-center"' +
                                ' style="color: white; height: 22px; width: 22px; background-color: var(--danger); border-radius: 4px;" data-id="' + id + '">' +
                                '<i class="bx bx-x"></i>' +
                                '</div>' +
                                '</div>';
                        }
                    },
                    {
                        data: 'kredit',
                        orderable: false,
                        className: 'text-center middle-header',
                        render: function (data) {
                            let id = data['id'];
                            if (data) {
                                return '<div class="w-100 d-flex justify-content-center align-items-center gap-1">' +
                                    '<div class="d-flex justify-content-center align-items-center"' +
                                    ' style="color: white; height: 22px; width: 22px; background-color: var(--success); border-radius: 4px;" data-id="' + id + '">' +
                                    '<i class="bx bx-check"></i>' +
                                    '</div>' +
                                    '</div>';
                            }
                            return '<div class="w-100 d-flex justify-content-center align-items-center gap-1">' +
                                '<div class="d-flex justify-content-center align-items-center"' +
                                ' style="color: white; height: 22px; width: 22px; background-color: var(--danger); border-radius: 4px;" data-id="' + id + '">' +
                                '<i class="bx bx-x"></i>' +
                                '</div>' +
                                '</div>';
                        }
                    },
                    {
                        data: null,
                        orderable: false,
                        className: 'text-center middle-header',
                        render: function (data) {
                            let id = data['id'];
                            let urlDetail = path + '/' + id + '/selesai';
                            return '<div class="w-100 d-flex justify-content-center align-items-center gap-1">' +
                                '<a style="color: var(--dark-tint)" href="' + urlDetail + '" class="btn-table-action" data-id="' + id + '"><i class="bx bx-dots-vertical-rounded"></i></a>' +
                                '</div>';
                        }
                    }
                ],
            });
        }

        function eventChangeTab() {
            $('#transaction-tab').on('shown.bs.tab', function (e) {
                if (e.target.id === 'pills-new-tab') {
                    table.columns.adjust();
                }

                if (e.target.id === 'pills-packing-tab') {
                    tablePacking.columns.adjust();
                }

                if (e.target.id === 'pills-finish-tab') {
                    tableFinish.columns.adjust();
                }
            })
        }

        $(document).ready(function () {
            eventChangeTab();
            generateTableNewOrder();
            generateTablePackingOrder();
            generateTableFinishOrder();
        });
    </script>
@endsection
