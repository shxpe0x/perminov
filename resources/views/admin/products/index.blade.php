<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Админка — товары</title>
</head>
<body>
<h1>Админка — товары</h1>

<p>
    <a href="{{ route('catalog.index') }}">Каталог</a>
    |
    <a href="{{ route('products.create') }}">+ Добавить товар</a>
    |
    <form action="{{ route('logout') }}" method="POST" style="display:inline">
        @csrf
        <button type="submit">Выйти</button>
    </form>
</p>

@if (session('success'))
    <div style="padding:10px; border:1px solid #0a0; background:#efe; margin:10px 0;">
        {{ session('success') }}
    </div>
@endif

@forelse ($products as $p)
    <div style="border:1px solid #ddd; padding:10px; margin:10px 0;">
        <div><strong>{{ $p->brand }} {{ $p->model }}</strong> ({{ $p->type }})</div>
        <div>Цена: {{ $p->price }}</div>

        <p>
            <a href="{{ route('products.edit', $p) }}">Редактировать</a>

            <form action="{{ route('products.destroy', $p) }}" method="POST" style="display:inline">
                @csrf
                @method('DELETE')
                <button type="submit" onclick="return confirm('Удалить товар?')">Удалить</button>
            </form>
        </p>
    </div>
@empty
    <p>Товаров нет.</p>
@endforelse

{{ $products->links() }}
</body>
</html>
