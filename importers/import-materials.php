<?php
/**
 * Created by Galicz Miklós.
 * BlackFyre Studio
 * http://blackworks.org
 * galicz.miklos@blackworks.org
 *
 * Date: 2012.12.28.
 * Time: 16:14
 */

ini_set("default_charset", "utf-8");
$db = new mysqli('localhost', 'root', '', 'opengallery');

$db->query("SET CHARACTER SET 'utf8'");
$db->query("SET COLLATION_CONNECTION = 'utf8_general_ci'");
$db->query("SET character_set_results = 'utf8'");
$db->query("SET character_set_server = 'utf8'");
$db->query("SET character_set_client = 'utf8'");

if (($handle = fopen("../src/materials.csv", "r")) !== FALSE) {
    while (($data = fgetcsv($handle, 0, ";", '"')) !== FALSE) {

        $en = $data[0];
        $hu = $data[1];

        $checkQuery = "SELECT * FROM _t_mat WHERE en='$en' AND hu='$hu'";

        $checkResult = $db->query($checkQuery);

        if ($checkResult->num_rows == 0) {
            $insertQuery = "REPLACE INTO _t_mat (`hu`,`en`) VALUES ('$hu','$en')";

            if ($db->query($insertQuery)) {
                echo 'OK<br />';
            } else {
                echo $db->error . '<br />';
            }
        }

    }
    fclose($handle);
}

$db->close();
