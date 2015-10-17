<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="container">
    <div class="row main">
        <div class="three columns navbar">
            <svg height="100" width="100">
                <circle cx="50" cy="50" r="40" stroke="black" stroke-width="3" fill="red" />
            </svg>
            <?php include __DIR__ . '/../partials/sidebar.php'; ?>
        </div>
        <div class="seven columns">
            <?php echo $content ?>
        </div>
    </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
