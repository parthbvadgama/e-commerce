<?php
session_start();
require_once __DIR__ . '/includes/db.php';
$categories = ['All Products','Electronics','Fashion','Home & Furniture','Accessories'];
$products = [];
try {
  $stmt = $pdo->query("SELECT id,name,category,price,image,description FROM products WHERE active=1 ORDER BY id DESC");
  $products = $stmt->fetchAll();
} catch (Throwable $e) { $products = []; }
$cartCount = array_sum($_SESSION['cart'] ?? []);
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>GoldCart | Premium Shopping</title><link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topbar"><a class="brand" href="index.php">GOLD<span>CART</span></a>
<nav><a href="index.php">Home</a><a href="#products">Products</a><a href="track.php">Track Order</a><a href="cart.php">Cart (<?= (int)$cartCount ?>)</a>
<?php if (!empty($_SESSION['user_id'])): ?><a href="logout.php">Logout</a><?php else: ?><a href="login.php">Login</a><?php endif; ?></nav></header>
<section class="hero"><div><p class="eyebrow">THE PREMIUM STORE</p><h1>Technology meets<br><span>timeless style.</span></h1><p>Discover electronics and everyday essentials, picked for you.</p><a class="btn" href="#products">Shop Collection</a></div><div class="hero-mark">GC</div></section>
<main class="container" id="products"><div class="section-head"><div><p class="eyebrow">CURATED FOR YOU</p><h2>Explore Products</h2></div><input id="search" class="search" placeholder="Search products..." aria-label="Search products"></div>
<div class="filters"><?php foreach ($categories as $cat): ?><button class="filter <?= $cat==='All Products'?'active':'' ?>" data-category="<?= htmlspecialchars($cat) ?>"><?= htmlspecialchars($cat) ?></button><?php endforeach; ?></div>
<div class="products" id="productGrid">
<?php if (!$products): ?>
  <div class="empty"><h3>Your store is ready to set up.</h3><p>Add products to the database using <code>database.sql</code> or your Adminer/phpMyAdmin tool.</p></div>
<?php endif; ?>
<?php foreach ($products as $p): ?>
<article class="product-card" data-name="<?= htmlspecialchars(strtolower($p['name'])) ?>" data-category="<?= htmlspecialchars($p['category']) ?>">
<img src="<?= htmlspecialchars($p['image'] ?: 'assets/placeholder.svg') ?>" alt="<?= htmlspecialchars($p['name']) ?>" onerror="this.src='assets/placeholder.svg'">
<div class="product-info"><span class="tag"><?= htmlspecialchars($p['category']) ?></span><h3><?= htmlspecialchars($p['name']) ?></h3><p><?= htmlspecialchars($p['description'] ?? '') ?></p><div class="product-bottom"><strong>₹<?= number_format((float)$p['price'],2) ?></strong><form action="cart.php" method="post"><input type="hidden" name="csrf" value="<?= htmlspecialchars(csrf_token()) ?>"><input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>"><button class="btn small" name="add" value="1">Add to Cart</button></form></div></div>
</article>
<?php endforeach; ?>
</div></main>
<footer><a class="brand" href="index.php">GOLD<span>CART</span></a><p>Premium shopping, thoughtfully selected.</p><small>Demo storefront · Configure hosting and payment before accepting real orders.</small></footer>
<script src="assets/app.js"></script></body></html>
