<?php

$fname = $lname = $fullname = $greeting = $img = "";
$fnameErr = $lnameErr = $imgErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['fname'])) {
        $fnameErr = "* First name is required ";
    } else {
        $fname = htmlspecialchars($_REQUEST["fname"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $fname)) {
            $fnameErr = "Only letters and white space allowed";
        }
    }

    if (empty($_POST["lname"])) {
        $lnameErr = "* Last name is required ";
    } else {
        $lname = htmlspecialchars($_REQUEST["lname"]);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $lname)) {
            $lnameErr = "Only letters and white space allowed";
        }
    }
    if ((!$fnameErr) && (!$lnameErr)) {

        $fullname = $fname . " " . $lname;
        $greeting = "Hello, " . $fullname;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PHP Assignment 1</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data"
            class="form-post">
            <h1>Welcome User</h1>

            <div class="field">
                <input type="text" placeholder="Enter first name" id="fname" name="fname" aria-label="first name"> <span
                    class="error"><?php echo $fnameErr; ?></span>

            </div>

            <div class="field">
                <input type="text" placeholder="Enter last name" id="lname" name="lname" aria-label="last name"> <span
                    class="error"><?php echo $lnameErr; ?></span>
            </div>

            <div class="field">
                <input type="text" placeholder="Full name is <?php echo $fullname; ?> " name="fullname"
                    aria-label="full name" disabled><span class="error"></span>
            </div>


            <button type="submit" value="Upload data">Submit</button>
        </form>
        <?php if ($greeting): ?>
            <h2>
                <?php echo $greeting; ?>
            </h2>
        <?php endif; ?>

    </div>
</body>

</html>