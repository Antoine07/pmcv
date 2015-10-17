<?php $title = 'Home page' ?>
<?php ob_start() ?>
<section class="content">
    <?php foreach ($posts as $post): ?>
        <h1><a href="<?php echo url('single', $post['id']); ?>">
                <?php echo $post['title'] ?>
            </a></h1>
        <?php if ($post['m_filename'] != null) : ?>
            <img src="<?php echo url('uploads', $post['m_filename']); ?>" alt=""/>
        <?php endif; ?>
        <p class="excerpt">lorem</p>
        <p class="date">
            <small>date de publication: <?php $datetime = new DateTime($post['published_at']);
                echo $datetime->format('d/m/Y') ?></small>
        </p>
    <?php endforeach; ?>
</section>
<?php $content = ob_get_clean() ?>
<?php include __DIR__ . '/../layouts/master.php' ?>