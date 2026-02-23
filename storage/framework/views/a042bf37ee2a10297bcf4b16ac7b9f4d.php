<nav class="navbar navbar-expand-lg navbar-afri sticky-top">
	<div
		class="container">
		<!-- Logo -->
		<a class="navbar-brand d-flex align-items-center" href="<?php echo e(route('home')); ?>">
			<div class="d-flex align-items-center justify-content-center me-2" style="width: 45px; height: 45px; background-color: var(--benin-green); border-radius: 50%;">
				<i class="bi bi-flower1 text-white fs-4"></i>
			</div>
			<div>
				<span class="fw-bold text-benin-green fs-5">TOTCHEMEGNON</span>
				<span class="d-block text-muted" style="font-size: 0.7rem; margin-top: -5px;">Bénin</span>
			</div>
		</a>

		<!-- Mobile Toggle -->
		<button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
			<i class="bi bi-list fs-4"></i>
		</button>

		<!-- Navigation Links -->
		<div class="collapse navbar-collapse" id="navbarContent">
			<ul class="navbar-nav mx-auto mb-2 mb-lg-0">
				<li class="nav-item">
					<a class="nav-link <?php echo e(request()->routeIs('home') ? 'active' : ''); ?>" href="<?php echo e(route('home')); ?>">
						<i class="bi bi-house-door me-1"></i>
						<?php echo e(__('messages.home')); ?>

					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?php echo e(request()->routeIs('artisans.*') ? 'active' : ''); ?>" href="<?php echo e(route('artisans.vue')); ?>">
						<i class="bi bi-tools me-1"></i>
						<?php echo e(__('Artisans & services')); ?>

					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?php echo e(request()->routeIs('gastronomie.*') ? 'active' : ''); ?>" href="<?php echo e(route('gastronomie.index')); ?>">
						<i class="bi bi-egg-fried me-1"></i>
						Gastronomies
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?php echo e(request()->routeIs('products.*') ? 'active' : ''); ?>" href="<?php echo e(route('products.index')); ?>">
						<i class="bi bi-palette me-1"></i>
						Arts & Artisanats
					</a>
				</li>
				<li class="nav-item">
					<a class="nav-link <?php echo e(request()->routeIs('culture.*') ? 'active' : ''); ?>" href="<?php echo e(route('culture.index')); ?>">
						<i class="bi bi-book me-1"></i>
						Cultures
					</a>
				</li>
			</ul>

			<!-- Right Side -->
			<div
				class="d-flex align-items-center gap-3">
				<!-- Language Selector -->
				<div class="dropdown lang-selector">
					<button class="btn btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-label="<?php echo e(__('messages.language')); ?>">
						<span
							class="me-1">
							<?php if(app()->getLocale() === 'fr'): ?>
                                🇫🇷
                            <?php elseif(app()->getLocale() === 'en'): ?>
                                                            🇬🇧
                                                        <?php else: ?>
                                                                                        🇧🇯
                                                                                    <?php endif; ?>
						</span>
						<?php echo e(strtoupper(app()->getLocale())); ?>

					</button>
					<ul class="dropdown-menu dropdown-menu-end">
						<li>
							<a class="dropdown-item <?php echo e(app()->getLocale() === 'fr' ? 'active' : ''); ?>" href="<?php echo e(route('lang.switch', 'fr')); ?>">
								<span class="me-2">🇫🇷</span>
								<?php echo e(__('messages.french')); ?>

							</a>
						</li>
						<li>
							<a class="dropdown-item <?php echo e(app()->getLocale() === 'en' ? 'active' : ''); ?>" href="<?php echo e(route('lang.switch', 'en')); ?>">
								<span class="me-2">🇬🇧</span>
								<?php echo e(__('messages.english')); ?>

							</a>
						</li>
						<li>
							<a class="dropdown-item <?php echo e(app()->getLocale() === 'fon' ? 'active' : ''); ?>" href="<?php echo e(route('lang.switch', 'fon')); ?>">
								<span class="me-2">🇧🇯</span>
								<?php echo e(__('fon')); ?>

							</a>
						</li>
					</ul>
				</div>

				<!-- Auth Buttons -->
				<?php if(auth()->guard()->check()): ?>
                    <div class="dropdown">
                        <button class="btn btn-outline-benin-green dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle me-1"></i>
                            <?php echo e(Auth::user()->name); ?>

                            <?php if(Auth::user()->hasRole('admin')): ?>
                                <span class="badge bg-benin-red ms-1">Admin</span>
                            <?php elseif(Auth::user()->hasRole('artisan')): ?>
                                <span class="badge bg-benin-green ms-1">Artisan</span>
                            <?php endif; ?>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if(auth()->guard()->check()): ?>
                                <li>
                                    <a class="dropdown-item" href="<?php echo e(route('admin.dashboard')); ?>">
                                        <i class="bi bi-speedometer2 me-2"></i>
                                        Mon espace
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li>
                                <a class="dropdown-item" href="<?php echo e(route('profile.edit')); ?>">
                                    <i class="bi bi-person me-2"></i>
                                    Profil</a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="<?php echo e(route('logout')); ?>">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>
                                        <?php echo e(__('messages.logout')); ?>

                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                <?php else: ?>
                    <a
                        href="<?php echo e(route('login')); ?>" class="btn btn-outline-benin-green btn-sm"><?php echo e(__('messages.login')); ?>

                    </a>
                    <a
                        href="<?php echo e(route('register')); ?>" class="btn btn-benin-green btn-sm"><?php echo e(__('messages.register')); ?>

                    </a>
                <?php endif; ?>
			</div>
		</div>
	</div>
</nav>

<?php /**PATH C:\xampp\htdocs\SAFEN_2026\resources\views/partials/navbar.blade.php ENDPATH**/ ?>