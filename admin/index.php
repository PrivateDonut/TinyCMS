<?php
session_start();
foreach (glob("engine/configs/*.php") as $filename) {
    require_once $filename;
}
foreach (glob("functions/*.php") as $filename) {
    require_once $filename;
}

if (!isset($_GET['page']))
    $page = 'dashboard';
else {
    if (preg_match('/[^a-zA-Z]/', $_GET['page']))
        $page = 'dashboard';
    else
        $page = $_GET['page'];
}

require_once '../engine/functions/account.php';
require_once '../engine/functions/database.php';
$account = new Account($_SESSION['username']);
$rank = $account->get_rank();

if ($rank < 1) {
    header("Location: /?page=home");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Panel</title>
    <link href="https://bootswatch.com/5/darkly/bootstrap.css" rel="stylesheet">
    <link href="../assets/css/prism-okaidia.css" rel="stylesheet">
    <style>
        .admin-panel-wrapper {
            display: flex;
            height: 100vh;
        }

        .sidebar {
            background-color: #212529;
            width: 250px;
        }

        .sidebar .nav-link i {
            margin-right: 10px;
        }

        .content {
            padding: 30px;
        }

        .admin-panel-header {

            padding: 1rem;
            text-align: center;
        }

        .admin-panel-header h3 {
            font-weight: bold;
            margin: 0;
        }

        .nav-link {
            color: #fff !important;
        }
    </style>
</head>

<body>
    <div class="admin-panel-wrapper">
        <nav class="sidebar">
            <div class="admin-panel-header">
                <h3>Admin Panel</h3>
            </div>

            <div class="sidebar-sticky">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active text-white" href="/admin/?page=dashboard">
                            <i class="fa fa-home"></i>
                            Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/?page=accounts">
                            <i class="fa fa-user"></i>
                            Accounts
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/?page=news">
                            <i class="fa fa-user"></i>
                            News
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin/?page=settings">
                            <i class="fa fa-cog"></i>
                            Settings
                        </a>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="content">
            <?php

            if (file_exists('pages/' . $page . '.php')) {
                include 'pages/' . $page . '.php';
            } else {
                include 'pages/404.php';
            }
            ?>
        </div>

    </div>


    <script src="//cdn.ckeditor.com/4.21.0/basic/ckeditor.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <!-- ADD NEWS -->
    <script>
        $(document).ready(function() {
            $("#add-news-btn").click(function() {
                $('#newsModal').modal('show');
            });
        });
    </script>
    <!-- CKEDITOR -->
    <script>
        CKEDITOR.replace('news-content');
        CKEDITOR.replace('edit-news-content');
    </script>

    <!-- EDIT NEWS -->
    <script>
        $(document).ready(function() {
            $(".edit-news-btn").click(function() {
                var newsId = $(this).data('id');
                var newsTitle = $(this).data('title');
                var newsContent = $(this).data('content');

                $("#edit-news-id").val(newsId);
                $("#edit-news-title").val(newsTitle);
                $("#edit-news-content").val(newsContent);

                $('#editNewsModal').modal('show');
            });
        });
    </script>



</body>