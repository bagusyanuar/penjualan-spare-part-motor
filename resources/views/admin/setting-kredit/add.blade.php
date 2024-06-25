@extends('admin.layout')

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
    <div class="d-flex justify-content-between align-items-center mb-1">
        <div>
            <p class="content-title">Setting Kredit</p>
            <p class="content-sub-title">Manajemen data setting kredit</p>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.setting-kredit') }}">Setting Kredit</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah</li>
            </ol>
        </nav>
    </div>
    <div class="card-content">
        <form method="post" id="form-data">
            @csrf
            <div class="w-100 mb-3">
                <label for="duration" class="form-label input-label">Jangka Waktu (minggu) <span
                        class="color-danger">*</span></label>
                <input type="number" placeholder="0" class="text-input" id="duration"
                       name="duration" value="0">
                @if($errors->has('duration'))
                    <span id="duration-error" class="input-label-error">
                        {{ $errors->first('duration') }}
                    </span>
                @endif
            </div>
            <div class="w-100 mb-3">
                <label for="interest" class="form-label input-label">Bunga (%) <span
                        class="color-danger">*</span></label>
                <input type="number" placeholder="0" class="text-input" id="interest"
                       name="interest" value="0">
                @if($errors->has('interest'))
                    <span id="interest-error" class="input-label-error">
                        {{ $errors->first('durainteresttion') }}
                    </span>
                @endif
            </div>
            <hr class="custom-divider"/>
            <div class="d-flex align-items-center justify-content-end w-100">
                <a href="#" class="btn-add" id="btn-save">
                    <i class='bx bx-check'></i>
                    <span>Simpan</span>
                </a>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script src="{{ asset('/js/helper.js') }}"></script>
    <script>
        function eventSave() {
            $('#btn-save').on('click', function (e) {
                e.preventDefault();
                AlertConfirm('Konfirmasi!', 'Apakah anda yakin ingin menyimpan data?', function () {
                    $('#form-data').submit();
                })
            })
        }

        $(document).ready(function () {
            eventSave();
        })
    </script>
@endsection
