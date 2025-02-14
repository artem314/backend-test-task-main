migrations/schema.init.sql:
-

- строка 10 `float` изменить на `int` , цену лучше хранить в int (минимальные единицы валюты, напр. копейки, центы и
  т.д. 1₽ = 100 коп.), это позволит избежать ошибок округления

-Класс src/Controller/JsonResponse.php:
-

- добавить метод `response()` , который возвращает обьект ответа, в качестве параметра принимает код, строковый тип
  статуса и возвращаемые данные, позволяет добиться единообразия возвращаемых ответов
- для удобства добавить строковые константы типа ответов

src/Controller/AddToCartController.php:
-

- строка 23, переименовать в `add` возникает путаница
- добавить проверку существования товара, возвращать ответ с помощью функции `response()` из
  `src/Controller/JsonResponse.php`
- добавить проверку ненулевого количества добавляемого товара, возвращать ответ с помощью функции `response()` из
  `src/Controller/JsonResponse.php`
- в функцию `addItem` сразу передавать объект товара
- изменить возврат ответа на функцию `response()` из `src/Controller/JsonResponse.php`

src/Controller/GetCartController.php:
-

- возвращать ответ с помощью функции `response()` из `src/Controller/JsonResponse.php`
- убрать варианта ответа 404, если функция `getCart` вернет пустую корзину это тоже успешный ответ, значит еще ничгео не
  добавили

src/Controller/GetProductsController.php:
- 

- получать результат поиска товаров с помощью функции `Repository\ProductRepository::getByCategory`
- Если ничего не найдено возвращать ответ 404 с помощью функции `response()` из `src/Controller/JsonResponse.php`
- Ответ возвращать с помощью функции `response()` из `src/Controller/JsonResponse.php

src/Domain/Cart.php:
-

- `readonly private Customer $customer` заменить на `private ?Customer $customer`, покупателя может не быть (напр. не
  авторизован) реализуется, но возможность выбора товаров остается
- `readonly private string $paymentMethod` убрать из конструктора, тип платежа может в любой момент поменяться
- `private array $items` убрать из конструктора, товары должны добавляться через метод
- добавить сеттеры для `paymentMethod` и покупателя

src/Domain/CartItem.php:
-

- `public string $uuid` заменить на `private string $uuid`
- `public int $quantity` заменить на `private int $quantity`
- убрать `public float $price` и `public int $quantity`
- в конструктор сразу передавать объект товара
- добавить функцию `getProduct`
- изменить функцию получения полной цены товара `getPrice()`, необходимо здесь, чтобы, например, в этом классе
  расчитывать скидки на **КОНКРЕТНЫЕ** товары, купоны и проч. (правила, которые относятся напр. ко всей корзине (скидка
  при общей сумме, здесь не реализуются))
- убрать функцию `getProductUuid()`

src/Domain/Customer.php:
-

- убрать свойство `readonly` у класса, оставить `readonly` только id, значения остальных полей могут меняться
- добавить функцию `getFullName` - получение ФИО
- добавить сеттеры для `$firstName $lastName $middleName`

src/Infrastructure/Connector.php:
-

- добавить классу свойство `readonly`
- `private Redis $redis;
  public function __construct($redis)
  {
  return $this->redis = $redis;
  }`
  заменить на   `public function __construct(private Redis $redis){}`

- `public function get(Cart $key)` заменить на `public function get(string $key)`
- `throw new ConnectorException('Connector error', $e->getCode(), $e);` заменить на
  `throw new ConnectorException('Unable to get ', $e->getCode(), $e, ['key' => $key]);`
- `public function set(string $key, Cart $value)` заменить на `public function set(string $key, mixed $value): void`
- `throw new ConnectorException('Connector error', $e->getCode(), $e);` заменить на
  `throw new ConnectorException('Unable to set ' . $key, $e->getCode(), $e, ['key' => $key, 'data' => $value]);`
- в функцию `has` добавить обработку исключений

src/Infrastructure/ConnectorException.php:
- 

- класс не может реализовывать интерфейс Throwable, необходимо наследовать класс Exception
- изменить конструктор в соответствии с классом Exception
- добавить в конструктор `private readonly mixed $params = []`, параметры которые были использованы при вызове функции (
  объект, массив и тд)
- убрать лишние методы
- в метод `__toString()` добавить форматирование передаваемых параметров

src/Infrastructure/ConnectorFacade.php:
-

- добавить свойство класса `readonly`
- добавить обекта класса `src/Infrastructure/Connector.php` как приватное поле и сделать его геттер
- изменить метод build, неправильный порядок установления соединения и проверки подключения

src/Repository/Entity/Product.php

- убрать свойство `readonly` значения полей класса могут изменяться
- изменить тип свойства `price` на `int`
- добавить геттеры полям

src/Repository/CustomerManager.php:
- 

- добавить класс загрузки текущего покупателя

src/Repository/CartManager.php:
-

- убрать наследование от ConnectorFacade, передавать его в конструкторе
- добавить в конструктор `src/Repository/CustomerManager`
- функция `saveCart` set неправильный порядок аргументов
- заменить в try catch `Exception` на `ConnectorException`
- в функции `getCart` добавить получение текущего покупателя из `src/Repository/CustomerManager.php`

src/Repository/ProductRepository.php:
-

- строка 19 поменять возвращаемый тип на `?Product`
- строка 21 некорректная функция необходимо изменить `fetchOne ` на `fetchAssociative`
- строка 22 добавить проверку статуса `is_active = 1`, параметр запроса передавать с помощью аргументов, а не
  конкатенацией строки
- строка 25 изменить проверку, функция `fetchAssociative` в случае неудачи возвращает false
- строка 26 Возвращать `null` если ничего не найдено
- строка 37 заменить `id` на `*`
- строка 37 параметр запроса передавать с помощью аргументов, а не конкатенацией строки

src/View/CurrencyConverter.php:
-
- добавить класс, с методами которые конвертируют цену товара из минимальных единиц валюты в основные единицы и наоборот

src/View/CartView.php:
-

- убрать из конструктора ProductRepository, за ненадобностью
- добавить в конструктор `ProductsView`

- исправления функция `toArray`
    - вынести отдельно получение текущего покупателя, если нет информации, то возвращать пустой массив
    - получение всех элементов корзины вынести за цикл foreach `$items = $cart->getItems();`
    - значение total получать с помощью функции `getPrice()` класса `CartItem`
    - строка 41 `'total' => $total,` убрать, некорректный подсчет общей суммы
    - `$product = $this->productRepository->getByUuid($item->getProductUuid());` заменить на `$item->getProduct();`
      элемент корзины уже хранит в себе объект товара, нет необходимости получать его запросом
    - добавить форматирование цены с помощью функции  `CurrencyConverter::convertToMajor`
      - `'price' => $item->getPrice()` заменить на `'price' =>  CurrencyConverter::convertToMajor($cartItemPrice)`
      - `'price' => $product->getPrice()` заменить на `CurrencyConverter::convertToMajor($product->getPrice()),`
      - `$data['total'] = $total;` заменить на `$data['total'] = CurrencyConverter::convertToMajor($total);`
      - создание массива `'product' => []` заменить на вызов функции класса `ProductsView toArray()`

src/View/ProductsView.php:
-

- убрать из конструктора `ProductRepository` разнести логику получения данных и их представления
- функция `toArray`
  - изменить тип аргумента на `array $products` для большей возможности переиспользования
  - в итоговом массиве добавить `name`
  - добавить форматирование цены с помощью функции  `CurrencyConverter::convertToMajor`
  - `$this->productRepository->getByCategory($category)` заменить на `$products`

**Дополнительно (то что нужно сделать, но не реализовано)**

- Для получения списка товаров надо добавить пагинацию


