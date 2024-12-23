<!-- resources/views/dashboard/kontakkami.blade.php -->

@extends('layouts.dashboard')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Kontak Kami</h1>

    <div class="card">
        <div class="card-header">
            <h5>Hubungi Kami</h5>
        </div>
        <div class="card-body">
            <p>Jika ada pertanyaan, silakan hubungi kami melalui informasi di bawah ini:</p>

            <ul>
                <li><strong>Alamat:</strong> Jalan Contoh No.123, Kota ABC</li>
                <li><strong>Email:</strong> info@contoh.com</li>
                <li><strong>Telepon:</strong> +62 812 3456 7890</li>
            </ul>

            <p>Atau, Anda bisa mengisi form kontak di bawah ini:</p>

            <form>
                <div class="form-group">
                    <label for="name">Nama</label>
                    <input type="text" class="form-control" id="name" placeholder="Nama Anda">
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="Email Anda">
                </div>

                <div class="form-group">
                    <label for="message">Pesan</label>
                    <textarea class="form-control" id="message" rows="3" placeholder="Pesan Anda"></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Kirim</button>
            </form>
        </div>
    </div>
</div>
@endsection
