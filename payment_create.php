<?php
require_once __DIR__.'/includes/db.php';
require_once __DIR__.'/includes/config.php';
require_login();
function razorpay_api(string $method,string $path,?array $payload=null): array {
 if(!function_exists('curl_init')) throw new RuntimeException('PHP cURL enabled નથી. php.ini માં extension=curl enable કરીને Apache restart કરો.');
 $ch=curl_init(RAZORPAY_API_BASE.$path);
 curl_setopt_array($ch,[CURLOPT_RETURNTRANSFER=>true,CURLOPT_CUSTOMREQUEST=>$method,CURLOPT_HTTPAUTH=>CURLAUTH_BASIC,CURLOPT_USERPWD=>RAZORPAY_KEY_ID.':'.RAZORPAY_KEY_SECRET,CURLOPT_HTTPHEADER=>['Content-Type: application/json'],CURLOPT_TIMEOUT=>25]);
 if($payload!==null) curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($payload));
 $raw=curl_exec($ch);$http=(int)curl_getinfo($ch,CURLINFO_HTTP_CODE);curl_close($ch);
 $data=json_decode((string)$raw,true);
 if($http<200||$http>=300||!is_array($data)) throw new RuntimeException('Razorpay order create થયું નથી. Test API keys ચકાસો.');
 return $data;
}
if($_SERVER['REQUEST_METHOD']!=='POST'){header('Location: checkout.php');exit;} verify_csrf();
$cart=$_SESSION['cart']??[];$address=trim($_POST['address']??'');$error='';
try{
 if(!$cart) throw new RuntimeException('Cart ખાલી છે.');
 if(strlen($address)<10||strlen($address)>2000) throw new RuntimeException('સંપૂર્ણ delivery address લખો.');
 $ids=array_map('intval',array_keys($cart));$marks=implode(',',array_fill(0,count($ids),'?'));
 $st=$pdo->prepare("SELECT id,name,price FROM products WHERE active=1 AND id IN ($marks)");$st->execute($ids);$items=$st->fetchAll();
 if(count($items)!==count($ids)) throw new RuntimeException('Cart ફરી તપાસો; કોઈ product ઉપલબ્ધ નથી.');
 $total=0;foreach($items as $it)$total+=(float)$it['price']*max(1,min(99,(int)($cart[$it['id']]??1)));
 if($total<=0||$total>1000000)throw new RuntimeException('Order amount યોગ્ય નથી.');
 $orderNo='GC'.date('ymd').strtoupper(bin2hex(random_bytes(4)));
 $pdo->beginTransaction();
 $st=$pdo->prepare("INSERT INTO orders(user_id,order_no,total,address,status,payment_status) VALUES(?,?,?,?,'Pending','Unpaid')");
 $st->execute([$_SESSION['user_id'],$orderNo,number_format($total,2,'.',''),$address]);$localId=(int)$pdo->lastInsertId();
 $line=$pdo->prepare("INSERT INTO order_items(order_id,product_id,product_name,price,quantity) VALUES(?,?,?,?,?)");
 foreach($items as $it)$line->execute([$localId,$it['id'],$it['name'],$it['price'],max(1,min(99,(int)($cart[$it['id']]??1)))]);
 $pdo->commit();
 $rp=razorpay_api('POST','/orders',['amount'=>(int)round($total*100),'currency'=>'INR','receipt'=>substr($orderNo,0,40),'notes'=>['goldcart_order_no'=>$orderNo]]);
 $st=$pdo->prepare("UPDATE orders SET razorpay_order_id=? WHERE id=?");$st->execute([$rp['id'],$localId]);
 $_SESSION['pending_payment_order']=$localId;$_SESSION['pending_payment_rp_order']=$rp['id'];$_SESSION['pending_payment_order_no']=$orderNo;$_SESSION['pending_payment_amount']=(int)round($total*100);
 header('Location: pay.php');exit;
}catch(Throwable $e){if($pdo->inTransaction())$pdo->rollBack();$error=$e->getMessage();}
?><!doctype html><html lang="gu"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><link rel="stylesheet" href="assets/style.css"><main class="form-wrap"><div class="panel"><h1>Checkout Error</h1><p class="notice"><?=htmlspecialchars($error)?></p><a class="btn" href="checkout.php">પાછા જાઓ</a></div></main>
