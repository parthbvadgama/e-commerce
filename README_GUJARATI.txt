GOLDCART — Dreamweaver E-commerce Starter
========================================

જરૂરી વસ્તુઓ:
- XAMPP (Apache + MySQL + PHP)
- Adobe Dreamweaver
- Browser

1) GoldCart folder ને XAMPP ના htdocs folder માં મૂકો.
   ઉદાહરણ: C:\xampp\htdocs\GoldCart

2) XAMPP Control Panel ખોલો અને Apache તથા MySQL Start કરો.

3) Browser માં ખોલો:
   http://localhost/phpmyadmin

4) phpMyAdmin માં Import પસંદ કરો અને GoldCart/database.sql ફાઇલ Import કરો.
   જો SQL tab વાપરો તો આખું database.sql paste/run કરો.

5) Dreamweaver માં Site > New Site પસંદ કરો.
   Site Name: GoldCart
   Local Site Folder: C:\xampp\htdocs\GoldCart
   પછી index.php ખોલો.

6) Browser માં ચલાવો:
   http://localhost/GoldCart/

7) Register page માંથી account બનાવો:
   http://localhost/GoldCart/register.php

8) એ account ને admin બનાવવા phpMyAdmin > goldcart > SQL માં ચલાવો:
   UPDATE users SET role='admin' WHERE email='તમારું-email@example.com';
   પછી logout/login કરો. Admin panel:
   http://localhost/GoldCart/admin.php

9) Admin Panel માં products add કરો અને image URL/path આપો.
   Relative path ઉદાહરણ: assets/placeholder.svg
   પોતાની image ને project folder માં મૂકી path લખી શકો.

મહત્વપૂર્ણ:
- Checkout હાલ demo order બનાવે છે; કોઈ વાસ્તવિક પૈસા વસૂલતું નથી.
- Razorpay/બીજી payment gateway માટે merchant account, HTTPS, server-side order creation,
  signature verification અને webhook verification ઉમેરો. Secret key ક્યારેય frontendમાં ન મૂકો.
- Live website માટે secure database credentials, HTTPS, backups, validation, rate limiting,
  privacy policy અને shipping/return policies જરૂરી છે.
- Admin role માત્ર પોતાના trusted account ને આપો.
- Production માં PHP errors જાહેર ન કરો અને database root account ને blank password સાથે ન વાપરો.
