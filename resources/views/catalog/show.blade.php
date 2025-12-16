<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>{{ $product->brand }} {{ $product->model }}</title>
</head>
<body>
<p><a href="{{ route('catalog.index') }}">← Назад в каталог</a></p>

<h1>{{ $product->brand }} {{ $product->model }}</h1>

<div>Тип: {{ $product->type }}</div>
<div>Цена: {{ $product->price }}</div>

@if ($product->description)
    <h3>Описание</h3>
    <p>{{ $product->description }}</p>
@endif

</body>
</html>
