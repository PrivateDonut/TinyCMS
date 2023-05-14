<?php
if (isset($_POST['submit'])) {
    $news = new News();
    $news->add_news($_POST['news-title'], $_POST['news-content'], $_SESSION['username']);
}

if (isset($_POST['edit-submit'])) {
    $news = new News();
    $news->update_news($_POST['news-id'], $_POST['news-title'], $_POST['news-content']);
}
$news = new News();
?>

<div class="container mt-4">
    <h1>News Management</h1>
    <button class="btn btn-primary my-2" id="add-news-btn">Add News</button>

    <table class="table table-bordered" id="news-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Content</th>
                <th>Author</th>
                <th>Last Edited By</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($news->get_news(1, 10) as $newsItem) : ?>
                <tr>
                    <td><?php echo $newsItem['id']; ?></td>
                    <td><?php echo $newsItem['title']; ?></td>
                    <td><?php echo $newsItem['content']; ?></td>
                    <td><?php echo $newsItem['author']; ?></td>
                    <td><?php echo $newsItem['edit_by']; ?></td>
                    <td>
                    <button class="btn btn-primary edit-news-btn" data-id="<?php echo $newsItem['id']; ?>" data-title="<?php echo htmlspecialchars($newsItem['title'], ENT_QUOTES, 'UTF-8'); ?>" data-content="<?php echo htmlspecialchars($newsItem['content'], ENT_QUOTES, 'UTF-8'); ?>">Edit</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- News Modal -->
<div class="modal fade" id="newsModal" tabindex="-1" role="dialog" aria-labelledby="newsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newsModalLabel">Add News</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="news-form" method="post" action="">
                    <div class="form-group">
                        <label for="news-title" class="col-form-label">Title:</label>
                        <input type="text" class="form-control" id="news-title" name="news-title">
                    </div>
                    <div class="form-group">
                        <label for="news-content" class="col-form-label">Content:</label>
                        <textarea class="form-control" id="news-content" name="news-content"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="news-form" id="submit" name="submit">Save</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit News Modal -->
<div class="modal fade" id="editNewsModal" tabindex="-1" role="dialog" aria-labelledby="editNewsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editNewsModalLabel">Edit News</h5>
            </div>
            <div class="modal-body">
                <form id="edit-news-form" method="post" action="">
                    <input type="hidden" id="edit-news-id" name="news-id">
                    <div class="form-group">
                        <label for="edit-news-title" class="col-form-label">Title:</label>
                        <input type="text" class="form-control" id="edit-news-title" name="news-title">
                    </div>
                    <div class="form-group">
                        <label for="edit-news-content" class="col-form-label">Content:</label>
                        <textarea class="form-control" id="edit-news-content" name="news-content"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary" form="edit-news-form" id="edit-submit" name="edit-submit">Save</button>
            </div>
        </div>
    </div>
</div>