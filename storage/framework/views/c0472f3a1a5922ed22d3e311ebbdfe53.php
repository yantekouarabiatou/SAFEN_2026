<footer class="footer-afri py-5">
    <div class="container">
        <div class="row g-4">
            <!-- Brand -->
            <div class="col-lg-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="d-flex align-items-center justify-content-center me-2"
                         style="width: 50px; height: 50px; background-color: var(--benin-green); border-radius: 50%;">
                        <i class="bi bi-flower1 text-white fs-4"></i>
                    </div>
                    <div>
                        <span class="fw-bold text-white fs-5">TOTCHEMEGNON</span>
                        <span class="d-block text-benin-yellow" style="font-size: 0.8rem;">Bénin</span>
                    </div>
                </div>
                <p class="text-white-50 mb-3">
                    L'artisanat béninois à portée de clic. Découvrez, comprenez et acquérez l'artisanat béninois authentique grâce à l'intelligence artificielle.
                </p>
                <div class="d-flex gap-3">
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle" aria-label="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle" aria-label="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                    
                    <a href="#" class="btn btn-outline-light btn-sm rounded-circle" aria-label="WhatsApp">
                        <i class="bi bi-whatsapp"></i>
                    </a>
                </div>
            </div>

            <!-- Quick Links -->
            <div class="col-6 col-lg-2">
                <h6 class="text-benin-yellow fw-bold mb-3"><?php echo e(__('messages.home')); ?></h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="<?php echo e(route('home')); ?>"><?php echo e(__('footer.home')); ?></a></li>
                    <li class="mb-2"><a href="<?php echo e(route('artisans.index')); ?>"><?php echo e(__('footer.artisans')); ?></a></li>
                    <li class="mb-2"><a href="<?php echo e(route('gastronomie.index')); ?>"><?php echo e(__('footer.gastronomy')); ?></a></li>
                    <li class="mb-2"><a href="<?php echo e(route('products.index')); ?>"><?php echo e(__('footer.marketplace')); ?></a></li>
                    <li class="mb-2"><a href="<?php echo e(route('culture.index')); ?>"><?php echo e(__('footer.culture')); ?></a></li>
                </ul>
            </div>

            <!-- Services -->
            <div class="col-6 col-lg-2">
                <h6 class="text-benin-yellow fw-bold mb-3"><?php echo e(__('footer.footer_services')); ?></h6>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="<?php echo e(route('register')); ?>"><?php echo e(__('footer.become_artisan')); ?></a></li>
                    <li class="mb-2"><a href="<?php echo e(route('faq')); ?>"><?php echo e(__('footer.faq')); ?></a></li>
                    <li class="mb-2"><a href="<?php echo e(route('help')); ?>"><?php echo e(__('messages.help')); ?></a></li>
                    <li class="mb-2"><a href="<?php echo e(route('contact.create')); ?>"><?php echo e(__('messages.contact')); ?></a></li>
                </ul>
            </div>

            <!-- Newsletter -->
            <div class="col-lg-4">
                <h6 class="text-benin-yellow fw-bold mb-3"><?php echo e(__('footer.newsletter')); ?></h6>
                <p class="text-white-50 small">
                    <?php echo e(__('footer.subscribe')); ?>

                </p>
                <form class="d-flex gap-2" id="newsletter-form">
                    <input type="email" class="form-control form-control-sm" placeholder="<?php echo e(__('footer.email')); ?>"
                           style="border-radius: 20px;" required>
                    <button type="submit" class="btn btn-benin-yellow btn-sm px-3" style="border-radius: 20px;">
                        <i class="bi bi-send"></i>
                    </button>
                </form>

                <!-- Payment Methods -->
                <div class="mt-4">
                    <p class="text-white-50 small mb-2">Paiements acceptés :</p>
                    <div class="d-flex gap-2">
                        <span class="badge bg-light text-dark">Kkiapay</span>
                        <span class="badge bg-light text-dark">MTN MoMo</span>
                        <span class="badge bg-light text-dark">Moov Money</span>
                        <span class="badge bg-light text-dark">Visa</span>
                    </div>
                </div>
            </div>
        </div>

        <hr class="my-4 border-secondary">

        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start">
                <p class="text-white-50 small mb-0">
                    <?php echo e(__('footer.copyright')); ?>

                </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="<?php echo e(route('legal')); ?>" class="text-white-50 small me-3"><?php echo e(__('footer.legal')); ?></a>
                <a href="<?php echo e(route('privacy')); ?>" class="text-white-50 small me-3"><?php echo e(__('footer.privacy')); ?></a>
                <a href="<?php echo e(route('terms')); ?>" class="text-white-50 small"><?php echo e(__('footer.terms')); ?></a>
            </div>
        </div>
    </div>
</footer>
<?php /**PATH C:\xampp\htdocs\SAFEN_2026\resources\views/partials/footer.blade.php ENDPATH**/ ?>