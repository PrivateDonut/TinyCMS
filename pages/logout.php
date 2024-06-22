<?php
// Improve the logout functionality later

session_destroy();
header("Location: ?page=home");
exit;
?>