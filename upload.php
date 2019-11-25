<?php
if (isset($_POST["submit"])) {

    $mimes = array('application/vnd.ms-excel','text/plain','text/csv','text/tsv');

    //checking file type while uploading
    if (in_array($_FILES['file']['type'], $mimes)) {

        if (isset($_FILES["file"])) {

            //if there was an error uploading the file
            if ($_FILES["file"]["error"] > 0) {
                echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
            } else {
                //Print file details
                echo "Upload: " . $_FILES["file"]["name"] . "<br />";
                echo "Type: " . $_FILES["file"]["type"] . "<br />";
                echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
                echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br />";

                //if file already exists
                if (file_exists("upload/" . $_FILES["file"]["name"])) {
                    echo $_FILES["file"]["name"] . " already exists. ";
                } else {
                    //Store file in directory "upload" with the name of "uploaded_file.txt"
                    $storagename = "domains.csv";
                    move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $storagename);
                    echo "Stored in: " . "upload/" . $_FILES["file"]["name"] . "<br />";
                    echo "<h2>File uploaded Succsessfully.</h2>";
                }
            }
        } else {
            echo "No file selected <br />";
        }
    } else {
        die("Sorry, File type not allowed");
    }
}
?>
<a href="checkwhois.php">Check Whois Record Now.</a>
