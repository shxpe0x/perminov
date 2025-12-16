# 👁️ Глазик для пароля

## 📦 Что добавлено

Создан Blade-компонент `<x-password-input>` с кнопкой показа/скрытия пароля.

---

## 🚀 Как использовать

### 1. В форме входа (`resources/views/auth/login.blade.php`)

**Было:**
```blade
<input type="password" name="password" required>
```

**Стало:**
```blade
<x-password-input 
    name="password" 
    required 
    placeholder="Введите пароль"
/>
```

---

### 2. В форме регистрации (`resources/views/auth/register.blade.php`)

**Было:**
```blade
<input type="password" name="password" required>
<input type="password" name="password_confirmation" required>
```

**Стало:**
```blade
<!-- Пароль -->
<x-password-input 
    name="password" 
    required 
    placeholder="Придумайте пароль"
/>

<!-- Подтверждение пароля -->
<x-password-input 
    name="password_confirmation" 
    required 
    placeholder="Повторите пароль"
/>
```

---

## 🛠️ Что нужно для работы

### 1. Alpine.js (уже должен быть в Laravel Breeze)

Если его нет, добавь в `<head>` любого layout:
```html
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
```

### 2. Tailwind CSS (уже должен быть)

Компонент использует Tailwind-классы. Если у тебя свой CSS, перепиши стили.

---

## 🎨 Как это выглядит

Поле ввода пароля с кнопкой справа:
- **Закрытый глаз**: пароль скрыт (точки)
- **Открытый глаз**: пароль виден (текст)

При клике по глазу - переключается.

---

## ✅ Дополнительные параметры

Можешь добавить любые HTML-атрибуты:
```blade
<x-password-input 
    name="password" 
    required 
    autocomplete="current-password"
    placeholder="Введите пароль"
    class="custom-class"
/>
```

---

## 🔧 Если что-то не работает

### Alpine.js не работает
Добавь в `resources/views/layouts/app.blade.php` (или главный layout):
```html
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
```

### Стили не применяются
Запусти:
```bash
npm run dev
```

---

## 🎉 Готово!

Теперь у тебя красивые поля пароля с показом/скрытием! 👁️
