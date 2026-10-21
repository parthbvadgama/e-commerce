<?php
require_once __DIR__ . '/includes/db.php';
if ($_SERVER['REQUEST_METHOD']==='POST') {
  verify_csrf();
  if (isset($_POST['add'])) { $id=(int)($_POST['product_id']??0); if($id>0){$_SESSION['cart'][$id]=($_SESSION['cart'][$id]??0)+1;} header('Location: cart.php'); exit; }
  if (isset($_POST['update'])) { foreach(($_POST['qty']??[]) as $id=>$qty){$id=(int)$id;$qty=max(0,min(99,(int)$qty));if($qty===0)unset($_SESSION['cart'][$id]);else $_SESSION['cart'][$id]=$qty;} header('Location: cart.php');exit; }
}
$cart=$_SESSION['cart']??[];$items=[];$total=0;
if($cart){$ids=array_map('intval',array_keys($cart));$marks=implode(',',array_fill(0,count($ids),'?'));$st=$pdo->prepare("SELECT id,name,price FROM products WHERE id IN ($marks) AND active=1");$st->execute($ids);$items=$st->fetchAll();}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Your Cart | GoldCart</title><link rel="stylesheet" href="assets/style.css"></head><body>
<header class="topbar"><a class="brand" href="index.php">GOLD<span>CART</span></a><nav><a href="index.php">Continue Shopping</a><a href="track.php">Track Order</a></nav></header>
<main class="container"><p class="eyebrow">YOUR SELECTION</p><h1>Your Shopping Cart</h1>
<?php if(!$items): ?><div class="panel"><p>Your cart is empty.</p><a class="btn" href="index.php">Browse Products</a></div><?php else: ?>
<form method="post"><input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>"><div class="panel table-wrap"><table><thead><tr><th>Product</th><th>Price</th><th>Quantity</th><th>Subtotal</th></tr></thead><tbody>
<?php foreach($items as $item): $qty=$cart[$item['id']]??1;$sub=$qty*(float)$item['price'];$total+=$sub; ?><tr><td><?= htmlspecialchars($item['name']) ?></td><td>₹<?= number_format($item['price'],2) ?></td><td><input type="number" min="0" max="99" name="qty[<?= (int)$item['id'] ?>]" value="<?= (int)$qty ?>" style="width:85px"></td><td>₹<?= number_format($sub,2) ?></td></tr><?php endforeach; ?>
</tbody></table><p><button class="btn" name="update" value="1">Update Cart</button></p><h2>Total: ₹<?= number_format($total,2) ?></h2><p class="muted">Checkout requires a logged-in account.</p><a class="btn" href="checkout.php">Proceed to Checkout</a></div></form><?php endif; ?>
</main></body></html>
