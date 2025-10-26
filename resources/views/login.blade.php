<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Web Masrha</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        /* Definisi Warna Biru Muda/Cyan Baru */
        :root {
            --primary-color: #00bcd4;
            /* Cyan 500 */
            --primary-dark: #0097a7;
            /* Cyan 700 */
            --primary-light: #4cdefa;
            /* Cyan 300 */
            --bg-gradient: linear-gradient(135deg, #4cdefa, #0097a7);
            /* Gradient background baru */
        }

        body {
            font-family: 'Poppins', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-gradient);
        }

        .card {
            border: none;
            border-radius: 1.25rem;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
            overflow: hidden;
            background-color: #ffffff;
            /* Batasi lebar maksimal card untuk tampilan lebih baik */
            max-width: 450px;
            width: 90%;
        }

        .login-image {
            /* Hapus properti yang tidak lagi diperlukan untuk tata letak samping */
            height: 200px;
            /* Atur ketinggian gambar agar terlihat di atas form */
            background: url('https://1.bp.blogspot.com/-85w3nKQ5gOg/XQs8JTEA3FI/AAAAAAAAQ8o/ja1bjNAnsCYYGoN1VKFKqqw12EEXKuuGACLcBGAs/s1600/Ma%2527soem%2BUniversity.jpg') center/cover no-repeat;
            position: relative;
        }

        .login-image::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: rgba(0, 188, 212, 0.1);
        }

        /* Hilangkan pembulatan atas pada card untuk gambar, dan berikan pembulatan pada gambar itu sendiri */
        .card .row.g-0>div:first-child .login-image {
            border-top-left-radius: 1.25rem;
            border-top-right-radius: 1.25rem;
        }

        .form-control {
            border-radius: 0.5rem;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(0, 188, 212, 0.35);
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease;
        }

        .btn-primary:hover,
        .btn-primary:active,
        .btn-primary:focus {
            background-color: var(--primary-dark) !important;
            border: none !important;
            box-shadow: 0 4px 8px rgba(0, 188, 212, 0.3);
        }

        .brand-title {
            font-weight: 700;
            color: var(--primary-dark);
            letter-spacing: 0.5px;
            font-size: 1.75rem;
        }

        .text-primary {
            color: var(--primary-color) !important;
        }

        a.text-primary:hover {
            color: var(--primary-dark) !important;
        }

        .alert-danger {
            background-color: #ffdddd;
            border-color: #ffaaaa;
            color: #d8000c;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="">
                    <div class="row g-0">
                        <div class="col-12">
                            <div class="login-image"></div>
                        </div>

                        <div class="col-12 bg-white p-5">
                            <div class="text-center mb-4">
                                <h3 class="brand-title">Web Marsha</h3>
                                {{-- <p class="text-muted small-link">Silakan masuk untuk melanjutkan</p> --}}
                            </div>

                            @if ($errors->any())
                                <div class="alert alert-danger small py-2" role="alert">
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login') }}">
                                @csrf
                                @method('POST')
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email" class="form-control" required
                                        autofocus placeholder="nama@email.com" value="{{ old('email') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input type="password" name="password" id="password" class="form-control" required
                                        placeholder="Masukkan password Anda">
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="remember" name="remember">
                                        <label class="form-check-label small" for="remember">
                                            Ingat saya
                                        </label>
                                    </div>
                                    {{-- Link Lupa Password (Opsional) --}}
                                    <a href="#" class="small-link text-decoration-none text-primary">Lupa
                                        password?</a>
                                </div>

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary py-2 fw-semibold">
                                        <i class="bi bi-box-arrow-in-right me-1"></i> Masuk
                                    </button>
                                </div>
                            </form>

                            {{-- <div class="text-center mt-4 small-link">
                                Belum punya akun?
                                <a href="#" class="text-primary text-decoration-none fw-semibold">Daftar
                                    sekarang</a>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</body>

</html>
