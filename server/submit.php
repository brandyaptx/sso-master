<?php
/**
 * Created by PhpStorm.
 * User: lwp
 * Date: 2015/6/26
 * Time: 10:53
 */
// Execute controller command



function deal(){
    if (empty($_REQUEST['answer'])) fail("answer缺失");
    if (empty($_REQUEST['id'])) fail("id缺失");

    $conn = @mysql_connect("localhost", "root", "mobilenewspaper") or die("数据库链接错误");
    $right = array(8,4,4,1,1,2,1,1,2,4,8,8,4,2,1,4,4,1,1,8,4,1,8,7,15,7,7,5,7,3);
    list($score,$type) = calc($_REQUEST['answer'],$right);
    mysql_select_db("qa", $conn);
    $result = mysql_query("select id from questionnaire order by id desc limit 1",$conn);
    $row = mysql_fetch_assoc($result);
    $id = $row['id']+1;
    $SQL = "INSERT INTO questionnaire (titleid, score, answer) VALUES ( ".$_REQUEST['id'].",".str($score).",\"". $_REQUEST['answer']."\")";
    $score = $SQL;
//    $SQL="SELECT * FROM questionnaire order by id desc";
    $query=mysql_query($SQL,$conn);
    resubmit($type,$score,$id);


//    echo "ok";


}

function calc($answer , $right){
    $qa = explode("|" , $answer);
    $type = explode("=" , $qa[0])[1];
    $sum = 0;
    foreach( $qa as $key => $value){
        if($key != 0 ){
            $a = explode("=",$value)[1];
            if ($a == $right[$key-1] )
                $sum++;
        }
    }
    return  array( $sum,$type);
}

function resubmit($type , $score, $id){
    if( $type == 1)
        echo "<xmlmsg><result>$id</result><msg>企业</msg><scriptcode><![CDATA[comp_dis();$('#alert h4 span:first').html('您答对了".$score."题请填写个人信息');]]></scriptcode></xmlmsg>";
    elseif($type == 2)
        echo "<xmlmsg><result>$id</result><msg>个人</msg><scriptcode><![CDATA[sing_dis();]]></scriptcode></xmlmsg>";
    else
        echo "<xmlmsg><result>-1.0</result><msg>返回错误</msg><scriptcode><![CDATA[alert('返回错误');]]></scriptcode></xmlmsg>";
}

function fail($message){
    echo "<xmlmsg><result>-1.0</result><msg>".$message."</msg><scriptcode><![CDATA[alert('".$message."');]]></scriptcode></xmlmsg>";
    exit;
}


if ( isset($_GET['answer'])&&isset($_GET['id']) ) {
    deal();
}else{
    echo "<xmlmsg><result>-1.0</result><msg>参数错误</msg><scriptcode><![CDATA[alert('参数错误');]]></scriptcode></xmlmsg>";
}
?>