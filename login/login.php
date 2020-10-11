<?php
// Initialize the session
session_start();

function verifyUser($user_response_token) {
    //Verify captcha
    $post_data = http_build_query(
      array(
          'secret' => "6Ld7bdUZAAAAAB-9gl8L7Cm-G9RHR12KTAWmQBRo",
          'response' => $user_response_token,
      )
    );
    $opts = array('http' =>
      array(
          'method'  => 'POST',
          'header'  => 'Content-type: application/x-www-form-urlencoded',
          'content' => $post_data
      )
    );
    $context  = stream_context_create($opts);
    $response = file_get_contents('https://www.google.com/recaptcha/api/siteverify', false, $context);
    $result = json_decode($response);
    return $result->success;
}

// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true){
  header("location: welcome.php");
  exit;
}

// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$username = $password = "";
$username_err = $password_err = $captcha_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter username.";
    } else{
        $username = trim($_POST["username"]);
    }

    // Check if password is empty
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter your password.";
    } else{
        $password = trim($_POST["password"]);
    }

    if(isset($_POST["g-recaptcha-response"])){
      if (!verifyUser($_POST["g-recaptcha-response"])) {
        $captcha_err = "Please click the box";
      }
    }

    // Validate credentials
    if(empty($username_err) && empty($password_err) && empty($captcha_err)) {
        // Prepare a select statement
        $sql = "SELECT " . "username, password FROM users WHERE username = \"" . $username . "\"";
        if (mysqli_multi_query($link, $sql)) {
          if($result = mysqli_store_result($link)){
            var_dump($result);
              $hashed_password = mysqli_fetch_all($result)[0][1];
              // Check if username exists, if yes then verify password
              if (mysqli_num_rows($result) == 1) {
                  if (password_verify($password, $hashed_password)) {
                      // Password is correct, so start a new session
                      session_start();

                      // Store data in session variables
                      $_SESSION["loggedin"] = true;
                      $_SESSION["username"] = $username;

                      // Redirect user to welcome page
                      header("location: welcome.php");
                  } else {
                      // Display an error message if password is not valid
                      $password_err = "The password you entered was not valid.";
                  }
              } else {
                  // Display an error message if username doesn't exist
                  $username_err = "No account found with that username.";
              }
          }

            // Free result set
            mysqli_free_result($result);
        } else {
            echo mysqli_error($link);
        }
    }

    // Close connection
    mysqli_close($link);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>

    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

  </head>
<body>
    <div class="wrapper">
        <h2>Login</h2>
        <p>Please fill in your credentials to login.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                <label>Username</label>
                <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                <span class="help-block"><?php echo $username_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                <label>Password</label>
                <input type="password" name="password" class="form-control">
                <span class="help-block"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($captcha_err)) ? 'has-error' : ''; ?>">
              <div class="g-recaptcha" data-sitekey="6Ld7bdUZAAAAALHlDTZkrzu5exABoaDhzTZHkIGf"></div>
              <span class="help-block"><?php echo $captcha_err; ?></span>
            </div>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Login">
            </div>
            <p>Don't have an account? <a href="register.php">Sign up now</a>.</p>
        </form>
    </div>
</body>
</html>
