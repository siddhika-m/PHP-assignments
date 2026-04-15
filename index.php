<?php

$fname = $lname = $fullname = $greeting = $imgPath = "";
$fnameErr = $lnameErr = $imgErr = "";
$marksTable = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

  if (empty($_POST["fname"])) {
    $fnameErr = "* First name is required";
  } else {
    $fname = htmlspecialchars($_POST["fname"]);
    if (!preg_match("/^[a-zA-Z-' ]*$/", $fname)) {
      $fnameErr = "Only letters and white space allowed";
    }
  }

  if (empty($_POST["lname"])) {
    $lnameErr = "* Last name is required";
  } else {
    $lname = htmlspecialchars($_POST["lname"]);
    if (!preg_match("/^[a-zA-Z-' ]*$/", $lname)) {
      $lnameErr = "Only letters and white space allowed";
    }
  }

  if (isset($_POST["submit"]) && isset($_FILES["input-img"]) && $_FILES["input-img"]["error"] === 0) {

    $target_dir = "uploads/";
    $fileName = time() . "_" . basename($_FILES["input-img"]["name"]);
    $target_file = $target_dir . $fileName;

    $check = getimagesize($_FILES["input-img"]["tmp_name"]);
    if ($check === false) {
      $imgErr = "File is not an image.";
    }

    $allowedTypes = ['jpg', 'jpeg', 'png'];
    $imageFileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    if (!in_array($imageFileType, $allowedTypes)) {
      $imgErr = "Only JPG, JPEG, PNG allowed.";
    }

    if ($_FILES["input-img"]["size"] > 2 * 1024 * 1024) {
      $imgErr = "Max file size is 2MB.";
    }

    if (!$imgErr && !$fnameErr && !$lnameErr) {
      if (move_uploaded_file($_FILES["input-img"]["tmp_name"], $target_file)) {
        $imgPath = $target_file;
      } else {
        $imgErr = "Error uploading file.";
      }
    }
  }
  if (!$fnameErr && !$lnameErr && !$imgErr) {
    $fullname = $fname . " " . $lname;
    $greeting = "Hello, " . $fullname;
  }

  if (isset($_POST['submit'])) {

    $marksInput = $_POST['marks'];
    $lines = explode("\n", $marksInput);

    $marksTable .= "<table >";
    $marksTable .= "<tr><th>Subject</th><th>Marks</th></tr>";
    foreach ($lines as $line) {
      $line = trim($line);
      if (!empty($line)) {
        $parts = explode("|", $line);
        if (count($parts) == 2) {
          $subject = trim($parts[0]);
          $marks = trim($parts[1]);
          $marksTable .= "<tr>";
          $marksTable .= "<td>$subject</td>";
          $marksTable .= "<td>$marks</td>";
          $marksTable .= "</tr>";
        }
      }
    }

    $marksTable .= "</table>";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PHP Assignment</title>
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <div class="container">
    <div class="main" style="display: <?php echo isset($_POST['submit']) ? 'flex' : 'block'; ?>; gap:20px;">
      <div class="upload-section">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data"
          class="form-post">

          <h1>Welcome User</h1>

          <div class="field">
            <input type="text" placeholder="Enter first name" name="fname" value="<?php echo $fname; ?>">
            <span class="error"><?php echo $fnameErr; ?></span>
          </div>

          <div class="field">
            <input type="text" placeholder="Enter last name" name="lname" value="<?php echo $lname; ?>">
            <span class="error"><?php echo $lnameErr; ?></span>
          </div>

          <div class="field">
            <input type="text" value="<?php echo $fullname; ?>" placeholder="Full name" disabled>
          </div>

          <div class="field">
            <input type="file" accept="image/png, image/jpeg" name="input-img">
            <span class="error"><?php echo $imgErr; ?></span>
          </div>

          <div class="field">
            <textarea name="marks" rows="6" cols="45"
              placeholder="Enter marks in format: Subject|Marks&#10;English|80&#10;"></textarea>

          </div>

          <button type="submit" name="submit">Submit</button>

        </form>
      </div>
      <div class="uploaded-section">
        <?php if ($greeting): ?>
          <h2><?php echo $greeting; ?></h2>
        <?php endif; ?>
        <span class="error"><?php echo $imgErr; ?></span>
        <?php if ($imgPath): ?>
          <div class="image-preview">
            <h3>Your Uploaded Image:</h3>
            <img src="<?php echo $imgPath; ?>" width="200">
          </div>
        <?php endif; ?>
        <?php if ($marksTable): ?>
          <div class="marks-table">
            <h3>Your Marks:</h3>
            <?php echo $marksTable; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>

</html>