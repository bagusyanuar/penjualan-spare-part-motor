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
    <div class="w-100 d-flex justify-content-between align-items-center mb-3">
        <p class="page-title">Akun Saya</p>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('customer.home') }}">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">Akun Saya</li>
            </ol>
        </nav>
    </div>
    <div class="d-flex">
        <div class="categories-sidebar">
            <a href="{{ route('customer.account') }}" class="categories-link active">Akun Saya</a>
            <a href="{{ route('customer.order') }}" class="categories-link">Pesanan</a>
            <a href="{{ route('customer.logout') }}" class="categories-link">Logout</a>
        </div>
        <div class="flex-grow-1" style="padding-left: 25px">
            <form method="post" id="form-akun">
                @csrf
                <div class="w-100 mb-2">
                    <label for="email" class="form-label input-label">Email</label>
                    <div class="text-group-container">
                        <i class='bx bx-envelope'></i>
                        <input type="email" placeholder="email" class="text-group-input" id="email"
                               name="email" value="{{ $user->email }}">
                    </div>
                </div>
                <div class="w-100 mb-2">
                    <label for="username" class="form-label input-label">Username</label>
                    <div class="text-group-container">
                        <i class='bx bx-user'></i>
                        <input type="text" placeholder="username" class="text-group-input" id="username"
                               name="username" value="{{ $user->username }}">
                    </div>
                </div>
                <div class="w-100 mb-2">
                    <label for="name" class="form-label input-label">Nama Lengkap</label>
                    <div class="text-group-container">
                        <i class='bx bx-id-card'></i>
                        <input type="text" placeholder="contoh : alex turner" class="text-group-input" id="name"
                               name="name" value="{{ $user->customer->nama }}">
                    </div>
                </div>
                <div class="w-100 mb-2">
                    <label for="phone" class="form-label input-label">No. Hp</label>
                    <div class="text-group-container">
                        <i class='bx bx-phone'></i>
                        <input type="number" placeholder="contoh : 628647658xxx" class="text-group-input" id="phone"
                               name="phone" value="{{ $user->customer->no_hp }}">
                    </div>
                </div>
                <div class="w-100 mb-2">
                    <label for="address" class="form-label input-label">Alamat</label>
                    <div class="text-group-container">
                        <i class='bx bx-home'></i>
                        <input type="text" placeholder="contoh : jl. urip sumoharjo no. 8, surakarta"
                               class="text-group-input" id="address"
                               name="address" value="{{ $user->customer->alamat }}">
                    </div>
                </div>
                <hr class="custom-divider"/>
                <div class="d-flex justify-content-end">
                    <a href="#" id="btn-save" class="btn-action-primary" style="width: fit-content;">Simpan</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('/js/helper.js') }}"></script>
    <script>
        function eventSave() {
            $('#btn-save').on('click', function (e) {
                e.preventDefault();
                AlertConfirm('Konfirmasi!', 'Apakah anda yakin ingin merubah data?', function () {
                    $('#form-akun').submit();
                })
            })
        }

        $(document).ready(function () {
            eventSave();
        })
    </script>
@endsection
