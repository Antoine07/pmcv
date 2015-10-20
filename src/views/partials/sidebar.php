<nav class="nav__sidebar" id="nav__sidebar">
    <ul class="nav__menu" id="nav__menu">
        <li><a href="<?php echo url(); ?>">Home</a><span class="nav__spacer"></span></li>
        <?php if (auth_guest()) : ?>
            <li><a href="<?php echo url('dashboard'); ?>">Dashboard</a><span class="nav__spacer"></span></li>
            <li><a href="<?php echo url('logout'); ?>">Logout</a><span class="nav__spacer"></span></li>
        <?php else: ?>
            <li><a href="<?php echo url('login'); ?>"> login</a><span class="nav__spacer"></span></li>
        <?php endif; ?>
    </ul>
</nav>