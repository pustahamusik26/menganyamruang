<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Administrator — Arsip Anyaman: Kebudayaan Tionghoa Medan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>{!! file_get_contents(base_path('public/css/archive.css')) !!}</style>
    <style>{!! file_get_contents(base_path('public/css/admin.css')) !!}</style>
</head>
<body class="admin-body">
    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-card-header">
                <div class="login-brand-row">
                    <div class="chinese-seal-box" title="Menganyam Ruang">織</div>
                    <div>
                        <div class="login-kicker">PANEL MANAJEMEN ARSIP DIGITAL</div>
                        <div class="login-title">Arsip Anyaman // Medan</div>
                    </div>
                </div>
                <p class="login-subtitle">
                    Masuk untuk mengelola postingan Situs Budaya, Galeri Digital, Video Dokumenter, dan Profil Tim Kerja.
                </p>
            </div>

            <div class="login-card-body">
                @if(session('error'))
                    <div class="alert alert-error">{{ session('error') }}</div>
                @endif

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if($errors->any())
                    <div class="alert alert-error">
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login.submit') }}">
                    @csrf
                    <div class="form-group">
                        <label for="username" class="form-label">Username / Email Admin</label>
                        <input
                            type="text"
                            id="username"
                            name="username"
                            class="form-control"
                            value="{{ old('username', 'admin') }}"
                            placeholder="Masukkan username (contoh: admin)"
                            required
                            autofocus
                        >
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">Password</label>
                        <div style="position: relative;">
                            <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                placeholder="Masukkan password administrator"
                                required
                                style="padding-right: 72px;"
                            >
                            <button
                                type="button"
                                id="togglePwdBtn"
                                style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); background: var(--ivory-surface); border: 1px solid var(--wood-line); padding: 3px 8px; font-family: var(--font-mono); font-size: 10px; cursor: pointer;"
                            >
                                LIHAT
                            </button>
                        </div>
                    </div>

                    <div class="form-group" style="display: flex; align-items: center; justify-content: space-between; margin-top: 12px;">
                        <label style="display: inline-flex; align-items: center; gap: 8px; font-size: 13px; cursor: pointer;">
                            <input type="checkbox" name="remember" value="1">
                            <span>Ingat sesi login saya</span>
                        </label>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 12px; margin-top: 8px;">
                        MASUK KE PANEL ADMIN &rarr;
                    </button>
                </form>

                <div style="margin-top: 22px; padding-top: 16px; border-top: 1px solid var(--wood-line); display: flex; justify-content: space-between; align-items: center; font-size: 12px;">
                    <a href="{{ route('archive.home') }}" style="color: var(--wood-medium); text-decoration: none; font-family: var(--font-mono);">
                        &larr; KEMBALI KE BERANDA WEBSITE
                    </a>
                    <span style="font-family: var(--font-mono); font-size: 11px; color: var(--wood-muted);">
                        menganyamruang.id
                    </span>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('togglePwdBtn')?.addEventListener('click', function () {
            var pwd = document.getElementById('password');
            if (!pwd) return;
            if (pwd.type === 'password') {
                pwd.type = 'text';
                this.textContent = 'TUTUP';
            } else {
                pwd.type = 'password';
                this.textContent = 'LIHAT';
            }
        });
    </script>
</body>
</html>
