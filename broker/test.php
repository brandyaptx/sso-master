<?php

$ticket = $this->generate_ticket($appid, 60, $redis);
if(strpos($ret['data'],"?"))
{
    $ret['data'] .= "&ticket=$ticket";
}
else
{
    $ret['data'] .= "?ticket=$ticket";
}
$ret['data'] .= ($request->get('state')) ? "&state=".$request->get('state') : "";

function generate_ticket($appid, $timeout, $redis)
{
    $uuid = \J20\Uuid\Uuid::v4(false);;
    $ticket = md5($uuid.self::SALT);
    $data = array('sid' => session_id(), 'appid' => $appid);
    $redis->setex($ticket, 60, json_encode($data));
    return $ticket;
}




$ip = $config->redis->ip;
$port = $config->redis->port;

$redis = new \Redis();
$redis->connect($ip, $port);
$ticket = $request->get('ticket');
$resp = array('code' => \ecode\Ecode::OK);
if($redis->exists($ticket))
{
    $data = json_decode($redis->get($ticket), TRUE);
    $redis->delete($ticket);
    session_destroy();
    session_id($data['sid']);
    session_start();
    $resp['uid'] = $_SESSION['uid'];
    if(isset($_SESSION['ptoken']))
    {
        $resp['ptoken'] = $_SESSION['ptoken'];
    }
    else
    {
        $resp['ptoken'] = $_SESSION['ptoken'] = \account\Tools::str_random();
        }
    $ptlogout = \account\Tools::str_random();
    $resp['ptlogout'] = $ptlogout;
    $info = array('ptlogout' => $ptlogout, 'sid' => $request->get('sid'));
    $_SESSION['ptlogin'][$data['appid']] = $info;
}
else
{
    $resp['code'] = \ecode\Ecode::SSOTicketInvalid;
}
$resp['msg'] = constant("L::ecode_".$resp['code']);
return \account\Tools::json_ret($resp);
?>