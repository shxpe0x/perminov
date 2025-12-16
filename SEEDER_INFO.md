# 🏜Seeder с тестовыми данными

## 📋 Какие фотки найти и куда бросить

### Папка для фоток
```
public/images/products/
```

Создай эту папку если её нет.

---

## 📷 Названия файлов товаров

### Процессоры
1. `cpu-intel-i9.jpg` - Intel Core i9-13900K
2. `cpu-amd-ryzen.jpg` - AMD Ryzen 9 7950X
3. `cpu-intel-i7.jpg` - Intel Core i7-13700K

### Видеокарты
4. `gpu-nvidia-4090.jpg` - NVIDIA RTX 4090
5. `gpu-nvidia-4080.jpg` - NVIDIA RTX 4080
6. `gpu-amd-7900.jpg` - AMD RX 7900 XTX

### Материнские платы
7. `mobo-asus-z790.jpg` - ASUS ROG MAXIMUS Z790
8. `mobo-msi-x870.jpg` - MSI MEG X870-E

### Оперативная память
9. `ram-kingston-32gb.jpg` - Kingston Fury Beast 32GB DDR5
10. `ram-corsair-64gb.jpg` - Corsair Vengeance RGB 64GB DDR5

### SSD
11. `ssd-samsung-4tb.jpg` - Samsung 990 Pro 4TB
12. `ssd-wd-2tb.jpg` - WD Black SN850X 2TB

### Мониторы
13. `monitor-asus-240hz.jpg` - ASUS ROG Swift 1440p 240Hz
14. `monitor-lg-ultrawide.jpg` - LG UltraWide 3440x1440

### Клавиатуры
15. `keyboard-corsair.jpg` - Corsair K95 Platinum XT
16. `keyboard-logitech.jpg` - Logitech G915 Pro

### Мышки
17. `mouse-logitech-502.jpg` - Logitech G502 Hero
18. `mouse-razer-deathadder.jpg` - Razer DeathAdder V3

---

## ✅ где найти фотки

### Оптимальнрe решения:

1. **Google Images** - Найди "Intel i9 13900K", "нагружать изображение"
2. **Яндекс.Картинки** - По русски "RTX 4090"
3. **Производители** - На сайтах NVIDIA, AMD, Intel
4. **Amazon/Aliexpress** - Это топовые явно доступные

---

## 😱 Или простые чёрные пластинки

Eсли ленивый пноп, можешь любое рандомные картинки с Google. Товары всё равно тестовые! 😂

---

## 🚀 Как запустить сидер

### 1. Очистить и переделать БД
```bash
php artisan migrate:fresh --seed
```

### 2. Только сидер
```bash
php artisan db:seed
```

---

## 📋 Что создастся

### Пользователи:
- **Админ**: телефон `+79999999999`
- **Обычный**: телефон `+79876543210`
- **Пароль**: `password`

### Категории:
1. Процессоры
2. Видеокарты
3. Материнские платы
4. Оперативная память
5. Жёсткие диски
6. SSD
7. Мониторы
8. Клавиатуры
9. Мышки
10. Наушники

### Товары:
**20 продуктов** с реальными названиями и ценами товаров компьютерных магазинов.

---

## 💪 Мини гайд

1. Сохрани все 20 фотоко в папку `public/images/products/`
2. Называй они выше указанными именами
3. Расчиты с расширением `.jpg` или `.png`
4. Пусти `php artisan migrate:fresh --seed`
5. Отлично!

---

## 🌟 Теперь на сайте будет:

✅ Полная база товаров
✅ Разные ценые от 5к до 250к
✅ 10 категорий
✅ Тень для тестирования всего

Прийдеть очкень на защите! 🚀
