<!-- 
- Made By : PrivateDonut
- Project Name : TinyCMS
- Website : https://privatedonut.com
-->
<?php
session_start();
if (isset($_POST['logout'])) {
  session_destroy();
  header("Location: /?page=home");
  exit();
}
?>
