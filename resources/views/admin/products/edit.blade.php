<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Редактировать товар</title>
</head>
<body>
<h1>Редактировать товар</h1>

<p><a href="{{ route('products.index') }}">← Назад</a></p>

@if ($errors->any())
    <div style="padding:10px; border:1px solid #a00; background:#fee; margin:10px 0;">
        <div>Ошибки:</div>
        <ul>
            @foreach ($errors->all() as $e)
                <li>{{ $e }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('products.update', $product) }}">
    @csrf
    @method('PUT')

    <p>
        Тип:
        <select name="type">
            <option value="computer" @selected(old('type', $product->type) === 'computer')>computer</option>
            <option value="peripheral" @selected(old('type', $product->type) === 'peripheral')>peripheral</option>
        </select>
    </p>

    <p>Бренд: <input name="brand" value="{{ old('brand', $product->brand) }}"></p>
    <p>Модель: <input name="model" value="{{ old('model', $product->model) }}"></p>
    <p>Цена: <input name="price" type="number" min="0" value="{{ old('price', $product->price) }}"></p>

    <p>Описание:</p>
    <p><textarea name="description" rows="5" cols="50">{{ old('description', $product->description) }}</textarea></p>

    <button type="submit">Обновить</button>
</form>
</body>
</html>
