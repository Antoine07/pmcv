<?php include __DIR__ . '/../partials/header.php'; ?>
<div class="container">
    <div class="row main">
        <div class="row">
            <div class="alert">
                <h1></h1>
                <p><a data-response="yes" href="#">yes</a> <a data-response="no" href="#">no</a></p>
                <p><a class="close" href="#"> nothing X</a></p>
            </div>
            <?php echo $content ?>
        </div>
    </div>
<?php include __DIR__ . '/../partials/footer.php'; ?>