# hellman
Hellman Logistics

Install: 
composer.phar install

Browser: 
http:/web.server.local/gtin/1234567
http:/web.server.local/index.php/gtin/1234567

CLI:




Class usage:

$number = 1234567;

$gtin = new Gtin($number);
$fullGtin = $gtin->getFullGtinNumber();
or
$gtin = new Gtin();
$gtin->setGtinNumber($number); or 
$fullGtin = $gtin->getFullGtinNumber();


$checksum = 


UnitTest:
phpunit src/Tests/Gtin/GtinTest.php