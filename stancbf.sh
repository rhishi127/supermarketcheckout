   vendor/bin/phpstan analyse -l 5 module/Cart/src
   vendor/bin/phpstan analyse -l 5 module/Core/src
   vendor/bin/phpstan analyse -l 5 module/Discount/src
   vendor/bin/phpstan analyse -l 5 module/Domain/src
   vendor/bin/phpstan analyse -l 5 module/Product/src

  vendor/bin/phpcbf --standard=PSR2 module/Cart/src
  vendor/bin/phpcbf --standard=PSR2 module/Core/src
  vendor/bin/phpcbf --standard=PSR2 module/Discount/src
  vendor/bin/phpcbf --standard=PSR2 module/Domain/src
  vendor/bin/phpcbf --standard=PSR2 module/Product/src
