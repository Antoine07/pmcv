<?php ob_start() ?>
    <div class="container">
        <div class="row main">
            <div class="twelve columns">
                <section class="content">
                    <?php foreach ($post as $p): ?>
                    <?php $title = render($p['title'], false) ?>
                    <h1><?php render($p['title']) ?></h1>

                    <div class="body">
                        <?php render($p['content']) ?>
                    </div>
                </section>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
<?php $content = ob_get_clean() ?>
<?php include __DIR__ . '/../layouts/master.php' ?>