<?php
/**
 * Created by PhpStorm.
 * User: lwp
 * Date: 2015/6/26
 * Time: 10:53
 */
// Execute controller command



function deal(){
    if (empty($_REQUEST['username'])) fail("username缺失");
    if (empty($_REQUEST['id'])) fail("id缺失");
    if (empty($_REQUEST['phonenum'])) fail("phonenum缺失");

    $conn = @mysql_connect("localhost", "root", "mobilenewspaper") or die("数据库链接错误");
    mysql_select_db("qa", $conn);
    if ( isset($_GET['city'])&&isset($_GET['company']) ) {
        $SQL = "update questionnaire set city = \"" . $_REQUEST['city'] . "\" ,company = \"" . $_REQUEST['company'] . "\", username =\"" . $_REQUEST['username'] . "\",phonenum= " . $_REQUEST['phonenum'] . " where id = " . $_REQUEST['id'] ;
    }
    else {
        $SQL ="update questionnaire set  username =\"" . $_REQUEST['username'] . "\",phonenum= " . $_REQUEST['phonenum'] . " where id = " . $_REQUEST['id'] ;
    }
//    $SQL="SELECT * FROM questionnaire order by id desc";
//    $query=mysql_query($SQL);
    if (mysql_query($SQL))
    {
        echo $_REQUEST['callback'].'("'."<xmlmsg><result>-1.0</result><msg>提交成功</msg><scriptcode><![CDATA[alert('提交成功');]]></scriptcode></xmlmsg>".'")';

    }
    else
    {
        echo "Error creating database: " . mysql_error();
    }

//    echo "ok";


}

function fail($message){
    echo $_REQUEST['callback'].'("'."<xmlmsg><result>-1.0</result><msg>".$message."</msg><scriptcode><![CDATA[alert('".$message."');]]></scriptcode></xmlmsg>".'")';
    exit;
}

deal();

?>
