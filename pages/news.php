<!-- 
Made By: PrivateDonut
Project Name: TinyCMS
Website: https://privatedonut.com
-->

<?php
if (isset($_GET['id'])) {
    $newsId = $_GET['id'];

    $newsHome = new news_home();
    $news = $newsHome->get_news_by_id($newsId);

    if ($news) {
        $title = $news[0]['title'];
        $content = $news[0]['content'];
        $author = $news[0]['author'];
        $date = $news[0]['date'];
    } else {
        // If the news article is not found, redirect to a 404 page or display an error message
        header('Location: /404.php');
        exit();
    }
} else {
    // If no news ID is specified, redirect to a 404 page or display an error message
    header('Location: /404.php');
    exit();
}
?>

<div class="container mt-5">
    <div class="card">
        <div class="card-body">
            <h5 class="card-title"><?php echo $title; ?></h5>
            <p class="card-text"><?php echo $content; ?></p>
            <p class="card-text">Author: <?php echo $author; ?></p>
            <p class="card-text">Date: <?php echo $date; ?></p>
        </div>
    </div>
</div>
