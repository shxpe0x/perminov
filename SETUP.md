# 🚀 Руководство по установке Perminov Store

## 📋 Требования

- PHP 8.2+
- Composer
- Node.js 18+
- MySQL/PostgreSQL
- Git

---

## ⚙️ Установка

### 1. Клонирование репозитория

```bash
git clone https://github.com/shxpe0x/perminov.git
cd perminov
```

### 2. Установка зависимостей PHP

```bash
composer install
```

### 3. Настройка окружения

```bash
cp .env.example .env
php artisan key:generate
```

Отредактируй `.env`:

```env
APP_NAME="Perminov Store"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=perminov
DB_USERNAME=root
DB_PASSWORD=
```

### 4. База данных

```bash
# Создай базу данных
mysql -u root -p
CREATE DATABASE perminov;
exit;

# Запусти миграции
php artisan migrate

# Заполни тестовыми данными (опционально)
php artisan db:seed
```

### 5. Установка JavaScript зависимостей

```bash
npm install
npm run build
```

### 6. Создание символической ссылки для загрузок

```bash
php artisan storage:link
```

---

## 👨‍💼 Настройка админ-панели Filament

### 1. Установка Filament

```bash
composer require filament/filament:"^3.2" -W
php artisan filament:install --panels
```

### 2. Создание админа

```bash
php artisan make:filament-user
```

Введи данные:
- **Name:** Admin
- **Email:** admin@perminov.local
- **Password:** password (или свой пароль)

### 3. Добавление поля is_admin в таблицу users

Создай миграцию:

```bash
php artisan make:migration add_is_admin_to_users_table
```

Открой `database/migrations/xxxx_add_is_admin_to_users_table.php`:

```php
public function up()
{
    Schema::table('users', function (Blueprint $table) {
        $table->boolean('is_admin')->default(false)->after('email');
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('is_admin');
    });
}
```

Запусти миграцию:

```bash
php artisan migrate
```

### 4. Сделай пользователя админом

```bash
php artisan tinker
```

В консоли:

```php
$user = User::where('email', 'admin@perminov.local')->first();
$user->is_admin = true;
$user->save();
exit;
```

### 5. Создание страниц для OrderResource

```bash
php artisan make:filament-page ManageOrders --resource=OrderResource --type=ManageRecords
php artisan make:filament-page ViewOrder --resource=OrderResource --type=ViewRecord
php artisan make:filament-page EditOrder --resource=OrderResource --type=EditRecord
```

---

## 🏃 Запуск проекта

### Разработка (2 терминала)

**Терминал 1 - Laravel:**
```bash
php artisan serve
```

**Терминал 2 - Vite:**
```bash
npm run dev
```

### Или один командой (если установлен concurrently)

```bash
npm run dev:all
```

---

## 🌐 URL адреса

- **Главная:** http://localhost:8000
- **Каталог:** http://localhost:8000/catalog
- **Корзина:** http://localhost:8000/cart (требует авторизации)
- **Заказы:** http://localhost:8000/orders (требует авторизации)
- **👑 Админка:** http://localhost:8000/admin

---

## 🔑 Вход в админку

1. Открой: http://localhost:8000/admin
2. Введи:
   - **Email:** admin@perminov.local
   - **Password:** тот что ты указал при создании

---

## 📦 Что есть в админке

- ✅ **Товары** - CRUD с загрузкой изображений
- ✅ **Категории** - Управление категориями
- ✅ **Заказы** - Просмотр и изменение статусов
- ✅ **Пользователи** - Управление пользователями
- ✅ **Дашборд** - Статистика продаж

---

## 🛠️ Полезные команды

### Очистка кэша

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Пересоздание базы данных

```bash
php artisan migrate:fresh --seed
```

### Создание нового товара через Tinker

```bash
php artisan tinker
```

```php
Product::create([
    'type' => 'keyboard',
    'brand' => 'Logitech',
    'model' => 'G Pro X',
    'price' => 12990,
    'stock' => 15,
    'description' => 'Механическая клавиатура для профессионалов',
    'is_featured' => true,
]);
```

---

## 🐛 Решение проблем

### Не добавляется в корзину?

```bash
php artisan migrate:fresh
php artisan cache:clear
```

### Не видно изменений?

```bash
php artisan view:clear
Ctrl + F5 в браузере
```

### Ошибка 500?

Проверь логи:
```bash
tail -f storage/logs/laravel.log
```

---

## 📝 Типы товаров

- `computer` - Компьютеры
- `keyboard` - Клавиатуры
- `mouse` - Мыши
- `headphones` - Наушники
- `monitor` - Мониторы
- `webcam` - Веб-камеры
- `speaker` - Колонки

---

## 🎨 Популярные бренды по категориям

- **Клавиатуры:** Logitech, Corsair, Razer, HyperX
- **Мыши:** Logitech, Razer, SteelSeries, HyperX
- **Наушники:** Sony, JBL, HyperX, Razer
- **Мониторы:** Samsung, LG, ASUS, Dell
- **Веб-камеры:** Logitech, Microsoft, Razer, A4Tech
- **Колонки:** JBL, Logitech, Creative, Edifier
- **Компьютеры:** ASUS, MSI, HP, Dell

---

## 🚀 Готово!

Теперь у тебя есть полноценный интернет-магазин с современными фильтрами и админ-панелью! 🎉
