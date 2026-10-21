<?php
require_once __DIR__.'/includes/db.php';require_once __DIR__.'/includes/config.php';require_login();
function fetch_rp_payment(string $id):array{
 if(!function_exists('curl_init'))throw new RuntimeException('PHP cURL enabled નથી.');
 $ch=curl_init(RAZORPAY_API_BASE.'/payments/'.rawurlencode($id));curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_HTTPAUTH=>CURLAUTH_BASIC,CURLOPT_USERPWD=>RAZORPAY_KEY_ID.':'.RAZORPAY_KEY_SECRET,CURLOPT_TIMEOUT=>20]);$raw=curl_exec($ch);$http=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE);curl_close($ch);$d=json_decode((string)$raw,true);if($http<200||$http>=300||!is_array($d))throw new RuntimeException('Razorpay status verify ન થયું.');return $d;
}
if($_SERVER['REQUEST_METHOD']!=='POST'){header('Location: cart.php');exit;}verify_csrf();
$local=(int)($_SESSION['pending_payment_order']??0);$expected=(string)($_SESSION['pending_payment_rp_order']??'');$no=(string)($_SESSION['pending_payment_order_no']??'');$pid=(string)($_POST['razorpay_payment_id']??'');$oid=(string)($_POST['razorpay_order_id']??'');$sig=(string)($_POST['razorpay_signature']??'');
if(!$local||!$expected||!$no||!$pid||!$oid||!$sig||!hash_equals($expected,$oid)){http_response_code(400);exit('Payment details match થતા નથી. Order Paid નથી થયો.');}
if(!hash_equals(hash_hmac('sha256',$oid.'|'.$pid,RAZORPAY_KEY_SECRET),$sig)){http_response_code(400);exit('Signature verification failed. Order Paid નથી થયો.');}
try{$p=fetch_rp_payment($pid);$s=$pdo->prepare("SELECT total,razorpay_order_id FROM orders WHERE id=? AND user_id=?");$s->execute([$local,$_SESSION['user_id']]);$o=$s->fetch();if(!$o||!hash_equals((string)$o['razorpay_order_id'],$oid))throw new RuntimeException('Order mismatch.');
$paise=(int)round((float)$o['total']*100);if(($p['order_id']??'')!==$oid||(int)($p['amount']??-1)!==$paise||($p['currency']??'')!=='INR')throw new RuntimeException('Amount/order verification failed.');
if(($p['status']??'')!=='captured'){header('Location: payment_failed.php?pending=1');exit;}
$s=$pdo->prepare("UPDATE orders SET payment_status='Paid',razorpay_payment_id=? WHERE id=? AND user_id=? AND razorpay_order_id=?");$s->execute([$pid,$local,$_SESSION['user_id'],$oid]);
unset($_SESSION['cart'],$_SESSION['pending_payment_order'],$_SESSION['pending_payment_rp_order'],$_SESSION['pending_payment_order_no'],$_SESSION['pending_payment_amount']);header('Location: track.php?order='.urlencode($no));exit;
}catch(Throwable $e){http_response_code(400);echo 'Payment verify ન થયું. Order Paid mark થયો નથી. Razorpay Dashboardમાં status તપાસો.';}
