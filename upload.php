<?php
    session_start();

    $mimes = array('application/vnd.ms-excel','text/csv','text/tsv');

    //checking file type while uploading


        if (isset($_FILES["file"])) 
        {
            if (in_array($_FILES['file']['type'], $mimes)) 
            {

            //if there was an error uploading the file
                if ($_FILES["file"]["error"] > 0) 
                {
                    echo "Return Code: " . $_FILES["file"]["error"] . "<br />";
                } 
                else 
                {
                    //Print file details
                    echo "<div style=\"padding: 15px; background-color: #4CAF50; border-radius: 5px;\">";
                    echo "<p style=\"color:white;\">Uploaded File Name: " . $_FILES["file"]["name"] . "</p>";
                    echo "<p style=\"color:white;\">File Size: " . round(($_FILES["file"]["size"] / 1024)) . " Kb</p>";
                    
                    //if file already exists
                    if (file_exists("upload/" . $_FILES["file"]["name"])) 
                    {
                        echo $_FILES["file"]["name"] . " already exists. ";
                    } 
                    else 
                    {
                        $storagename = "domains.csv";
                        move_uploaded_file($_FILES["file"]["tmp_name"], "upload/" . $storagename);
                                                
                        //session variable to prevent access
                        $_SESSION['upload'] = true;

                        echo "<h2 style=\"color:white;\">File uploaded Succsessfully.</h2>";
                        echo "</div>";
                        echo "<div style=\"margin-top: 3rem;background-color: #8BC34A;padding: 3rem;border-radius: 5px;\">";
                        echo "<a style=\"text-decoration: none;color: white;padding: 10px;border: solid 2px #fff;border-radius: 5px;margin-top: 10px;margin-bottom: 10px;\" href=\"checkwhois.php\">Check Whois Record Now.</a>";
                          echo "</div>";
                    }

                }
            } 
            else 
            {
                die("<span style=\"padding: 15px;background: #F44336;color: white;font-size: 15px;border-radius: 5px;\">Sorry, File type not allowed</span>");
            }
        } 
        else 
        {
            echo "<span style=\"padding: 15px;background: #F44336;color: white;font-size: 15px;border-radius: 5px;\">No file selected.</span><br />";
        }

?>
