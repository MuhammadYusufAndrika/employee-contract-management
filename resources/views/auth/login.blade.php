<x-guest-layout>
    <div class="auth-container">
        <!-- Left Side - Login Form -->
        <div class="auth-left">
            <div class="auth-box">
                <!-- Logo and Title -->
                <div class="logo-container">
                    <div class="logo-shield">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div class="logo-text">
                        <h1 class="logo-title">E-Hubungan Industrial</h1>
                        <p class="logo-subtitle">PT Dahana</p>
                    </div>
                </div>

                <p class="auth-subtitle">Login Menggunakan Akun Yang Diberikan</p>

                <!-- Session Status -->
                <x-auth-session-status class="mb-3" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Address -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input 
                            id="email" 
                            class="form-control @error('email') is-invalid @enderror" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            placeholder="Inputkan email anda"
                            required 
                            autofocus 
                            autocomplete="username"
                        />
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="password-input-wrapper">
                            <input 
                                id="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                type="password" 
                                name="password" 
                                placeholder="Inputkan password anda"
                                required 
                                autocomplete="current-password"
                            />
                            <button type="button" class="password-toggle" onclick="togglePassword()">
                                <i class="bi bi-eye" id="toggleIcon"></i>
                            </button>
                        </div>
                        @error('password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- @if (Route::has('password.request'))
                        <div class="text-end">
                            <a class="forgot-link" href="{{ route('password.request') }}">
                                Forgot Password?
                            </a>
                        </div>
                    @endif --}}

                    <!-- Warning Message -->
                    <div class="alert-warning">
                        <div class="alert-icon">!</div>
                        <div>Apabila Anda kesulitan masuk, SiLahkan Hubungi 085669812501</div>
                    </div>

                    <!-- Partner Logos -->
                    <div class="partner-logos">
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 35px; height: 35px; background: linear-gradient(135deg, #FF0000, #8B0000); border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                                <i class="bi bi-building" style="color: white; font-size: 18px;"></i>
                            </div>
                            <span style="font-weight: 600; font-size: 13px; color: #333;">Danantara Indonesia</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 8px;">
                            <div style="width: 80px; height: 30px; background: #f8f9fa; border-radius: 6px; display: flex; align-items: center; justify-content: center; border: 1px solid #dee2e6;">
                                <span style="font-weight: 700; font-size: 14px; color: var(--dark-blue);">DAHANA</span>
                            </div>
                        </div>
                    </div>

                    <!-- Remember Me -->
                    <div class="form-check mb-3">
                        <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                        <label class="form-check-label" for="remember_me">
                            Remember me
                        </label>
                    </div>

                    <!-- Login Button -->
                    <button type="submit" class="btn-login">
                        Login
                    </button>

                    {{-- @if (Route::has('register'))
                        <div class="register-link">
                            Don't have an account? <a href="{{ route('register') }}">Register here</a>
                        </div>
                    @endif --}}
                </form>
            </div>
        </div>

        <!-- Right Side - Background Image -->
        <div class="auth-right"></div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('bi-eye');
                toggleIcon.classList.add('bi-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('bi-eye-slash');
                toggleIcon.classList.add('bi-eye');
            }
        }
    </script>
</x-guest-layout>
