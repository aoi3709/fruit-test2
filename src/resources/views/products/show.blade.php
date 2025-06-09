{{-- 不必要になったけれど削除するのが怖いので一旦置いておく --}}
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品詳細 - {{ $product->name }}</title>
    <link rel="stylesheet" href="{{ asset('css/show.css') }}">
</head>
<body>
    <div class="container">
        <div class="product-image">
            @if ($product->image)
                <img src="{{ asset($product->image) }}" alt="{{ $product->name }}">
            @else
                <img src="{{ asset('images/no-image.png') }}" alt="No Image"> {{-- 適切なパスに修正 --}}
            @endif
        </div>
        <div class="product-details">
            <h1>{{ $product->name }}</h1>
            <p class="price">&yen;{{ number_format($product->price) }}</p>
            <p><strong>商品説明:</strong></p>
            <p class="description">{{ $product->description }}</p>

            @if ($product->seasons->isNotEmpty())
                <p><strong>旬の季節:</strong></p>
                <div class="seasons">
                    @foreach ($product->seasons as $season)
                        <span>{{ $season->name }}</span>
                    @endforeach
                </div>
            @else
                <p><strong>旬の季節:</strong> 設定されていません</p>
            @endif

            <div class="button-group">
                {{-- 商品更新画面へのリンク --}}
                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-edit">変更する</a>

                {{-- 商品削除フォーム --}}
                <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-delete" onclick="return confirm('本当にこの商品を削除しますか？');">削除する</button>
                </form>

                {{-- 商品一覧画面へ戻るボタン --}}
                <a href="{{ route('products.index') }}" class="btn btn-secondary">一覧に戻る</a>
            </div>
        </div>
    </div>
</body>
</html>