<?php if (hasFlashMessage()) : ?>
    <div class="flash-message">
        <?php echo getFlashMessage(); ?>
    </div>
<?php endif; ?>