USE goldcart;
ALTER TABLE orders
  ADD COLUMN razorpay_order_id VARCHAR(64) NULL UNIQUE,
  ADD COLUMN razorpay_payment_id VARCHAR(64) NULL UNIQUE;
