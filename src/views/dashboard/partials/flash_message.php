<div class="ten columns info">
    <a class="button" href="<?php echo url('post/create'); ?>">Add post</a>
    <?php if (hasFlashMessage()) : ?>
        <div class="flash-message">
            <?php echo getFlashMessage(); ?>
        </div>
    <?php endif; ?>
</div>