<?php $title = 'Update post' ?>

<?php ob_start() ?>
    <form action="<?php echo url('post/' . $post['id']); ?>" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_method" value="PUT"/>
        <?php echo _token(); ?>
        <div class="row">
            <div class="six columns">
                <label for="title">title</label>
                <input class="u-full-width" type="text" placeholder="title" id="title" name="title"
                       value="<?php echo $post['title']; ?>">
                <?php echo errors('title'); ?>
            </div>
            <div class="six columns">
                <label for="status">Status</label>
                <select class="u-full-width" id="status" name="status">
                    <option value="published" <?php echo ($post['status'] == 'published') ? 'selected' : '' ?> >
                        published
                    </option>
                    <option value="unpublished"  <?php echo ($post['status'] == 'unpublished') ? 'selected' : '' ?> >
                        unpublished
                    </option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="twelve">
                <?php if ($post['m_id'] != null) : ?>
                    <img src="<?php echo url('uploads', $post['m_filename']); ?>"/>
                    <label for="delete_filename">Delete filename</label>
                    <input type="hidden" name="m_id" value="<?php echo $post['m_id']; ?>">
                    <input type="checkbox" name="delete_filename" value="yes">
                <?php else: ?>
                    <label for="file">file</label>
                    <input class="u-full-widtn button-file" type="file" id="file" name="file">
                <?php endif; ?>
            </div>
        </div>
        <label for="content">content</label>
        <textarea class="u-full-width" placeholder="Hi content …" name="content"
                  id="content"><?php echo $post['content']; ?></textarea>
        <label class="published_at">
            <input type="checkbox" name="published_at"
                   value="yes"  <?php echo ($post['published_at'] != '0000-00-00 00:00:00') ? 'checked' : '' ?>>
            <span class="label-body">date published now</span>
        </label>
        <input class="button-primary" type="submit" value="Submit">
    </form>
<?php $content = ob_get_clean() ?>

<?php include __DIR__ . '/../../layouts/dashboard.php' ?>