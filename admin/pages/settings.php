<?php
$settings = new Settings();
$config = $settings->get_config();

if (isset($_POST['submit'])) {
    $settings->update_config($_POST['config']);
}

?>

<form method="post">
    <h1>Website Settings</h1>
    <label for="website_title">Website Title</label>
    <input type="text" name="website_title" value="<?php echo $config['website_title']; ?>">
    <br>
    <label for="website_description">Website Description</label>
    <input type="text" name="website_description" value="<?php echo $config['website_description']; ?>">

    <!-- Repeat for other settings -->

    <br><input type="submit" name="submit" value="Save">
</form>
