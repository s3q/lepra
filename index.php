<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>lepra</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            background-color: #000 !important;
        }
    </style>
</head>

<body>

    <div class="mt-10">
        
        <h2>Send Mail : </h2>

        <?php

        require_once "lepra.php";

        if (isset($_POST["submit-maildata"])) {
            $email = $_POST["email"];
            $subject = $_POST["subject"];
            $message = $_POST["message"];


            $mail = new Sendmail($email, $subject, $message);

            echo "<div class='mt-20'>";
            $mail::print();
            echo "</div>";

            $mail::send();
        }

        ?>

        <form action="" method="post" class="form-inputs mt-30">
            <div class="group-input-vr">
                <label for="email">email :</label>
                <input type="email" name="email" id="email" class="custom-input bgc-transparent" value="<?php
                                                                                                        if (isset($_POST["submit"])) {
                                                                                                            echo $email;
                                                                                                        }
                                                                                                        ?>">
            </div>
            <div class="group-input-vr">
                <label for="email">subject :</label>
                <input type="text" name="subject" id="subject" class="custom-input bgc-transparent" value="<?php
                                                                                                            if (isset($_POST["submit"])) {
                                                                                                                echo $subject;
                                                                                                            }
                                                                                                            ?>">
            </div>
            <div class="group-input-vr">
                <label for="message">message :</label>
                <textarea name="message" id="message" cols="30" rows="10" class="custom-input bgc-transparent"><?php
                                                                                                                if (isset($_POST["submit"])) {
                                                                                                                    echo $message;
                                                                                                                }
                                                                                                                ?></textarea>
            </div>
            <div class="mt-10" style="width: max-content">
                <input type="submit" value="submit" name="submit-maildata" id="submit-maildata" class="bn bn-dark-blue">
            </div>
        </form>

    </div>

    <hr>

    <div class="mt-50">
        <h2>get ip : </h2>

        <div class="mt-30">
            <pre class="p-10 bo-dark m-center color-light" style="white-space: break-spaces; overflow: hidden;"><?php echo Userinfo::get_ip() . "<br>";
                                                                                                                echo Userinfo::get_browser() . "<br>";
                                                                                                                echo Userinfo::get_device() . "<br>";
                                                                                                                echo Userinfo::get_os() . "<br>" . "<br>"; ?></pre>
        </div>

    </div>


    <hr>

    <div class="mt-50">
        <h2>upload file : </h2>

        <div class="mt-30">
            <form action="" method="POST" enctype="multipart/form-data" class="form-inputs mt-30">
                <div class="group-input-vr">
                    <label for="fileToUpload" class="custom-file-label" style="background-color: transparent;">upload file :</label>
                    <input type="file" name="fileToUpload" id="fileToUpload" class="custom-file">
                </div>
                <div class="mt-10" style="width: max-content">
                    <input type="submit" value="upload" name="submit-uploadfile" id="submit-uploadfile" class="bn bn-dark-blue">
                </div>
            </form>

            <?php

            if (isset($_POST["submit-uploadfile"])) {

                $o = new Uploadfile($_FILES["fileToUpload"], "uploaded_files");

                $o::preparation(time());

                $o::print();

                $o::uploaded_file();
            }

            ?>
        </div>

    </div>


</body>

</html>

<?php

// require_once 'new.php';
