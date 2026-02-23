<?php $__env->startSection('title', 'Connexion - TOTCHEMEGNON'); ?>

<?php $__env->startSection('content'); ?>
<div class="min-vh-100 d-flex align-items-center py-5" style="background: linear-gradient(135deg, var(--beige) 0%, #ffffff 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card border-0 shadow-lg overflow-hidden" style="border-radius: 20px;">
                    <div class="row g-0">
                        <!-- Left Side - Branding -->
                        <div class="col-lg-6 d-none d-lg-block position-relative">
                            <div class="h-100 d-flex flex-column justify-content-center align-items-center text-white p-5"
                                 style="background: linear-gradient(135deg, var(--benin-green) 0%, var(--navy) 100%);">

                                <!-- Logo -->
                                <div class="mb-4">
                                    <div class="rounded-circle bg-white bg-opacity-25 d-inline-flex align-items-center justify-content-center mb-3"
                                         style="width: 100px; height: 100px; backdrop-filter: blur(10px);">
                                        <i class="bi bi-flower1 fs-1"></i>
                                    </div>
                                    <h2 class="fw-bold text-center">TOTCHEMEGNON</h2>
                                    <p class="text-center text-white-50">Bénin</p>
                                </div>

                                <!-- Features -->
                                <div class="w-100" style="max-width: 350px;">
                                    <div class="d-flex align-items-start mb-4">
                                        <div class="rounded-circle bg-benin-yellow d-flex align-items-center justify-content-center flex-shrink-0 me-3"
                                             style="width: 50px; height: 50px;">
                                            <i class="bi bi-palette text-charcoal fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Artisanat Authentique</h6>
                                            <small class="text-white-50">Découvrez des créations uniques</small>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-start mb-4">
                                        <div class="rounded-circle bg-benin-yellow d-flex align-items-center justify-content-center flex-shrink-0 me-3"
                                             style="width: 50px; height: 50px;">
                                            <i class="bi bi-robot text-charcoal fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">IA Culturelle</h6>
                                            <small class="text-white-50">Assistant intelligent Anansi</small>
                                        </div>
                                    </div>

                                    <div class="d-flex align-items-start">
                                        <div class="rounded-circle bg-benin-yellow d-flex align-items-center justify-content-center flex-shrink-0 me-3"
                                             style="width: 50px; height: 50px;">
                                            <i class="bi bi-people text-charcoal fs-5"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-1">Communauté Active</h6>
                                            <small class="text-white-50">Rejoignez 500+ artisans</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Decorative elements -->
                                <div class="position-absolute top-0 start-0 w-100 h-100" style="opacity: 0.1; z-index: 0;">
                                    <svg width="100%" height="100%">
                                        <pattern id="pattern" x="0" y="0" width="40" height="40" patternUnits="userSpaceOnUse">
                                            <circle cx="20" cy="20" r="2" fill="white"/>
                                        </pattern>
                                        <rect x="0" y="0" width="100%" height="100%" fill="url(#pattern)"/>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Right Side - Login Form -->
                        <div class="col-lg-6">
                            <div class="p-5">
                                <!-- Header -->
                                <div class="text-center mb-4">
                                    <h3 class="fw-bold text-charcoal mb-2">Bon retour !</h3>
                                    <p class="text-muted">Connectez-vous pour continuer votre aventure</p>
                                </div>

                                <!-- Session Status -->
                                <?php if(session('status')): ?>
                                    <div class="alert alert-success alert-dismissible fade show rounded-pill" role="alert">
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        <?php echo e(session('status')); ?>

                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                <?php endif; ?>

                                <!-- Login Form -->
                                <form method="POST" action="<?php echo e(route('login')); ?>">
                                    <?php echo csrf_field(); ?>

                                    <!-- Email -->
                                    <div class="mb-4">
                                        <label for="email" class="form-label fw-semibold text-charcoal">
                                            <i class="bi bi-envelope me-2 text-benin-green"></i>Email
                                        </label>
                                        <input id="email"
                                               type="email"
                                               name="email"
                                               value="<?php echo e(old('email')); ?>"
                                               required
                                               autofocus
                                               autocomplete="username"
                                               class="form-control form-control-lg <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                               placeholder="votre@email.com"
                                               style="border-radius: 12px; border: 2px solid #e0e0e0;">
                                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback">
                                                <i class="bi bi-exclamation-circle me-1"></i><?php echo e($message); ?>

                                            </div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <!-- Password -->
                                    <div class="mb-4">
                                        <label for="password" class="form-label fw-semibold text-charcoal">
                                            <i class="bi bi-lock me-2 text-benin-green"></i>Mot de passe
                                        </label>
                                        <div class="position-relative">
                                            <input id="password"
                                                   type="password"
                                                   name="password"
                                                   required
                                                   autocomplete="current-password"
                                                   class="form-control form-control-lg <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                   placeholder="••••••••"
                                                   style="border-radius: 12px; border: 2px solid #e0e0e0; padding-right: 45px;">
                                            <button type="button"
                                                    class="btn btn-link position-absolute top-50 end-0 translate-middle-y text-muted"
                                                    onclick="togglePassword()"
                                                    style="text-decoration: none;">
                                                <i class="bi bi-eye" id="toggleIcon"></i>
                                            </button>
                                        </div>
                                        <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback d-block">
                                                <i class="bi bi-exclamation-circle me-1"></i><?php echo e($message); ?>

                                            </div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>

                                    <!-- Remember Me & Forgot Password -->
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                        <div class="form-check">
                                            <input class="form-check-input"
                                                   type="checkbox"
                                                   name="remember"
                                                   id="remember_me"
                                                   style="border-radius: 6px;">
                                            <label class="form-check-label text-muted" for="remember_me">
                                                Se souvenir de moi
                                            </label>
                                        </div>

                                        <?php if(Route::has('password.request')): ?>
                                            <a href="<?php echo e(route('password.request')); ?>"
                                               class="text-benin-green text-decoration-none fw-semibold">
                                                Mot de passe oublié ?
                                            </a>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Submit Button -->
                                    <button type="submit"
                                            class="btn btn-benin-green btn-lg w-100 mb-3 fw-bold"
                                            style="border-radius: 12px; padding: 14px;">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                                    </button>

                                    <!-- Divider -->
                                    <div class="position-relative my-4">
                                        <hr class="text-muted">
                                        <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 text-muted small">
                                            ou
                                        </span>
                                    </div>

                                    <!-- Register Link -->
                                    <div class="text-center">
                                        <p class="text-muted mb-0">
                                            Vous n'avez pas de compte ?
                                            <a href="<?php echo e(route('register')); ?>" class="text-benin-green fw-bold text-decoration-none">
                                                Inscrivez-vous gratuitement
                                            </a>
                                        </p>
                                    </div>
                                </form>

                                <!-- Back to Home -->
                                <div class="text-center mt-4 pt-3 border-top">
                                    <a href="<?php echo e(route('home')); ?>" class="btn btn-outline-secondary rounded-pill">
                                        <i class="bi bi-arrow-left me-2"></i>Retour à l'accueil
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trust Badges -->
                <div class="row mt-4 text-center g-3">
                    <div class="col-6 col-md-3">
                        <div class="text-muted small">
                            <i class="bi bi-shield-check text-benin-green fs-4 d-block mb-2"></i>
                            Connexion sécurisée
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-muted small">
                            <i class="bi bi-people text-benin-green fs-4 d-block mb-2"></i>
                            500+ artisans
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-muted small">
                            <i class="bi bi-star-fill text-benin-yellow fs-4 d-block mb-2"></i>
                            4.8/5 satisfaction
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="text-muted small">
                            <i class="bi bi-globe text-benin-green fs-4 d-block mb-2"></i>
                            15 pays
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.guest', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\xampp\htdocs\SAFEN_2026\resources\views/auth/login.blade.php ENDPATH**/ ?>