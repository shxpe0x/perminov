<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Каталог</title>
</head>
<body>
<h1>Каталог</h1>

<form method="GET" action="{{ route('catalog.index') }}" style="border:1px solid #ddd; padding:10px; margin:10px 0;">
    <p>
        Тип:
        <select name="type">
            <option value="" @selected(!$type)>Все</option>
            <option value="computer" @selected($type === 'computer')>Компьютеры</option>
            <option value="peripheral" @selected($type === 'peripheral')>Периферия</option>
        </select>
    </p>

    <p>
        Поиск (brand/model):
        <input name="q" value="{{ $q ?? '' }}" placeholder="например: ASUS">
    </p>

    <p>
        Сортировка:
        <select name="sort">
            <option value="id" @selected(($sort ?? 'id') === 'id')>По ID</option>
            <option value="price" @selected(($sort ?? 'id') === 'price')>По цене</option>
        </select>

        <select name="dir">
            <option value="desc" @selected(($dir ?? 'desc') === 'desc')>убыв.</option>
            <option value="asc" @selected(($dir ?? 'desc') === 'asc')>возр.</option>
        </select>
    </p>

    <p>
        На странице:
        <select name="perPage">
            <option value="5" @selected(($perPage ?? 10) == 5)>5</option>
            <option value="10" @selected(($perPage ?? 10) == 10)>10</option>
            <option value="20" @selected(($perPage ?? 10) == 20)>20</option>
            <option value="50" @selected(($perPage ?? 10) == 50)>50</option>
        </select>
    </p>

    <button type="submit">Применить</button>
    <a href="{{ route('catalog.index') }}">Сбросить</a>
</form>

@if ($errors->any())
    <div style="padding:10px; border:1px solid #a00; background:#fee; margin:10px 0;">
        <div>Ошибки параметров:</div>
        <ul>
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

@forelse ($products as $p)
    <div style="border:1px solid #ddd; padding:10px; margin:10px 0;">
        <div><strong>{{ $p->brand }} {{ $p->model }}</strong></div>
        <div>Тип: {{ $p->type }}</div>
        <div>Цена: {{ $p->price }}</div>
        <a href="{{ route('catalog.show', $p) }}">Подробнее</a>
    </div>
@empty
    <p>Ничего не найдено.</p>
@endforelse

{{ $products->links() }}
</body>
</html>
