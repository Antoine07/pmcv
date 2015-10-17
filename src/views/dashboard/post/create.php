<?php $title = 'List of Posts' ?>

<?php ob_start() ?>
    <form action="<?php echo url('post/store'); ?>" method="post" enctype="multipart/form-data">
    <?php echo _token(); ?>
        <div class="row">
            <div class="six columns">
                <label for="title">title</label>
                <input class="u-full-width" type="text" placeholder="title" id="title" name="title"
                       value="<?php echo old('title'); ?>">
                <?php echo errors('title'); ?>
            </div>
            <div class="six columns">
                <label for="status">Status</label>
                <select class="u-full-width" id="status" name="status">
                    <option value="published">published</option>
                    <option value="unpublished">unpublished</option>
                </select>
            </div>
        </div>
        <div class="row">
            <div class="twelve">
                <?php echo errors('upload'); ?>
                <label for="file">file</label>
                    <input class="u-full-widtn button-file" type="file" id="file" name="file">
            </div>
        </div>
        <label for="content">content</label>
        <textarea class="u-full-width" placeholder="Hi content …" id="content"><?php echo old('content'); ?></textarea>
        <label class="published_at">
            <input type="checkbox" name="published_at" value="yes">
            <span class="label-body">date published now</span>
        </label>
        <input class="button-primary" type="submit" value="Submit">
    </form>
<?php $content = ob_get_clean() ?>

<?php include __DIR__ . '/../../layouts/dashboard.php' ?>