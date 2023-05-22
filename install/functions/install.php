<?php

class InstallTinyCMS
{
    public function checkExtension($extensionName)
    {
        if (extension_loaded($extensionName)) {
            echo "<span style='color: green; font-weight: bold;'>Enabled</span>";
        } else {
            echo "<span style='color: red; font-weight: bold;'>Disabled</span>";
        }
    }

    public function install($host, $port, $username, $password, $auth, $characters, $website, $soap_username, $soap_password)
    {
        $db = new mysqli($host, $username, $password, '', $port);
        if ($db->connect_error) {
            die('Connect Error (' . $db->connect_errno . ') ' . $db->connect_error);
        }

        $db_query = "CREATE DATABASE IF NOT EXISTS " . $website . ";";
        if ($db->query($db_query) === TRUE) {
            echo "Database created successfully";
            $db->select_db($website);
        } else {
            echo "Error creating database: " . $db->error;
        }

        $sql = file_get_contents(__DIR__ . '/../sql/website.sql');

        if ($db->multi_query($sql)) {
            do {
                if ($result = $db->store_result()) {
                    $result->free();
                }
            } while ($db->next_result());
        } else {
            echo "Error executing SQL script: " . $db->error;
        }

        $db->close();


        $config = fopen("../engine/configs/db_config.php", 'w');
        $txt = "<?php 
// Database Configuration
\$db_host = '" . $host . "';
\$db_port = '" . $port . "';
\$db_username = '" . $username . "';
\$db_password = '" . $password . "';
\$db_auth = '" . $auth . "';
\$db_characters = '" . $characters . "';
\$db_website = '" . $website . "';
    
// Soap Account Configuration
\$soap_username = '" . $soap_username . "';
\$soap_password = '" . $soap_password . "';   
?>";


        fwrite($config, $txt);
        fclose($config);
        header("Location: /?page=home");
        $_SESSION['success_message'] = "You have successfully installed TinyCMS!";

        // Create the install.lock file
        $file = fopen('../engine/install.lock', 'w');
        fclose($file);
    }
}
