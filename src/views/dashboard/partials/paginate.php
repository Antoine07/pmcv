<?php if (isset($num_page) && $num_page > 1) : ?>
    <div class="container">
        <div class="row">
            <div class="twelve columns">
                <ul class="paginate">
                    <?php if ($previous) : ?>
                        <li><a class="previous" href="<?php echo url('dashboard', ['previous', $lastIdPage - 1]); ?>"><<
                                previous</a>
                        </li>
                    <?php endif; ?>
                    <?php for ($i = 1; $i <= $num_page; $i++) : ?>
                        <li><a class="button-paginate <?php echo ($lastIdPage == $i) ? 'active' : ''; ?>"
                               href="<?php echo url('dashboard', $i); ?>"><?php echo $i; ?></a></li>
                    <?php endfor; ?>
                    <?php if ($next): ?>
                        <li><a class="previous" href="<?php echo url('dashboard', ['next', $lastIdPage + 1]); ?>">next
                                >></a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
<?php endif; ?>