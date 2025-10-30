<?php
require_once('config.php');

function tatcadanhmuc()
{
    global $conn;
    $sql = 'select * from category';
    return mysqli_query($conn, $sql);
}

function themmoidm ($tendm, $madm)
{
    global $conn;
    $sql = "insert into category values ('" . $madm . "','" . $tendm . "')";
    mysqli_query($conn, $sql);
}

function chinhsuadm ($madm, $tendm)
{
    global $conn;
    $sql = "update category set tendm = '" . $tendm . "' where category_id = '" . $madm . "'";
    mysqli_query($conn, $sql);
}

function xoadm($madm)
{
    global $conn;
    $sql = "delete from category where category_id = '" . $madm . "'";
    mysqli_query($conn, $sql);
}
?>
