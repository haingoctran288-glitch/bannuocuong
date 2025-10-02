<?php
    $host = 'localhost';//1
    $username = 'root';//2
    $password = '';//3
    $dbname = 'gecafe';//4
    //                          1        2          3         4
    $conn = mysqli_connect($host, $username, $password, $dbname);
    if(mysqli_connect_errno() != 0){
        echo 'LỖI KẾT NỐI';
    }

?>