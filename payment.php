<?php
// Include config file
require_once "login/config.php";

// Define variables and initialize with empty values

$card = $owner = $month = $year = $cvs = "";
$card_err = $owner_err = $month_err = $year_err = $cvs_err = "";


// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
  $card = $_POST['card'];
  $owner = $_POST['owner'];
  $month = $_POST['expiryMM'];
  $year = $_POST['expireYY'];
  $cvs = $_POST['cvs'];
  header("location: orderconfirm.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>'Payment'</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Payment</h2>
        <p>Please fill in your payment credientials.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group <?php echo (!empty($card_err)) ? 'has-error' : ''; ?>">
                <label>Card number</label>
                <input required type="text" name="card" class="form-control" value="<?php echo $card; ?>">
                <span class="help-block"><?php echo $card_err; ?></span>
            </div>
            <div class="form-group <?php echo (!empty($owner_err)) ? 'has-error' : ''; ?>">
                <label>Card owner</label>
                <input required type="text" name="owner" class="form-control" value="<?php echo $owner; ?>">
                <span class="help-block"><?php echo $owner_err; ?></span>
            </div>
              Card Expiration:
              <select name='expireMM' id='expireMM'>
                  <option value=''>Month</option>
                  <option value='01'>January</option>
                  <option value='02'>February</option>
                  <option value='03'>March</option>
                  <option value='04'>April</option>
                  <option value='05'>May</option>
                  <option value='06'>June</option>
                  <option value='07'>July</option>
                  <option value='08'>August</option>
                  <option value='09'>September</option>
                  <option value='10'>October</option>
                  <option value='11'>November</option>
                  <option value='12'>December</option>
              </select>
              <select name='expireYY' id='expireYY'>
                  <option value=''>Year</option>
                  <option value='10'>2010</option>
                  <option value='11'>2011</option>
                  <option value='12'>2012</option>
              </select>
              <input required class="inputCard" type="hidden" name="expiry" id="expiry" maxlength="4"/>
              <div class="form-group <?php echo (!empty($cvs_err)) ? 'has-error' : ''; ?>">
                  <label>CVS</label>
                  <input required type="text" name="cvs" class="form-control" value="<?php echo $cvs; ?>">
                  <span class="help-block"><?php echo $cvs_err; ?></span>
              </div>
              <div class="col-lg-12">
                <fieldset>
                    <button name="submit" value="comment" type="submit" id="form-submit" class="main-button pull-right">Confirm</button>
                </fieldset>
              </div>
        </form>
    </div>
</body>
</html>
