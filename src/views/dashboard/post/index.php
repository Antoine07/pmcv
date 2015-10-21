<?php $title = 'Crud' ?>
<?php ob_start() ?>
<?php include __DIR__ . '/../partials/flash_message.php' ?>
<?php include __DIR__ . '/../../partials/paginate.php' ?>
    <table class="u-full-width">
        <thead>
        <tr>
            <th>Status</th>
            <th>titre</th>
            <th>date publication</th>
            <th>delete</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($posts as $post): ?>
            <tr>
                <td><?php echo $post['status']; ?></td>
                <td><a href="<?php echo url('post/' . $post['id'] . '/edit'); ?>"><?php echo $post['title']; ?></a></td>
                <td><?php echo $post['published_at']; ?></td>
                <td>
                    <form class="form-delete" action="<?php echo url('post', $post['id']); ?>" method="post">
                        <input type="hidden" name="_method" value="DELETE"/>
                        <?php echo _token(); ?>
                        <input class="button btn-delete" data-title="<?php echo $post['title']; ?>" type="submit"
                               value="delete">
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php include __DIR__ . '/../../partials/paginate.php' ?>
<?php $content = ob_get_clean() ?>
<?php include __DIR__ . '/../../layouts/dashboard.php' ?>