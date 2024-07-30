@extends('admin.layout')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-1">
        <div>
            <p class="content-title">Angsuran</p>
            <p class="content-sub-title">Manajemen data angsuran</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.angsuran') }}">Angsuran</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $data->no_penjualan }}</li>
            </ol>
        </nav>
    </div>
    <div class="w-100 card-content">
        <div class="content-header mb-3">
            <p class="header-title">Data Angsuran</p>
        </div>
        <table id="table-data" class="display table w-100">
            <thead>
            <tr>
                <th width="5%" class="text-center"></th>
                <th width="12%" class="text-center">Tanggal</th>
                <th>Angsuran</th>
                <th width="12%" class="text-end">Total (Rp)</th>
                <th width="15%" class="text-center">Status</th>
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
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('js')
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
                    {orderable: false, targets: [0, 1, 2, 3]}
                ]
            });
        }
        $(document).ready(function () {
            generateTable();
        });
    </script>
@endsection
