<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品情報変更: {{ $product->name }}</title>
    <link rel="stylesheet" href="{{ asset('css/edit.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}"> </head>
<body>
  @php 
    use Illuminate\Support\Str;
  @endphp
    <header class="header">
        <div class="header__inner">
            <a class="header__logo" href="{{ route('products.index') }}">
                mogitate
            </a>
        </div>
    </header>

    <main class="product-edit-container">
        <div class="breadcrumb">
            <a href="{{ route('products.index') }}">商品一覧</a> &gt; {{ $product->name }}
        </div>

        <div class="product-edit-card">
            <div class="product-image-section">
                @if ($product->image)
                    @php
                    // $product->image が 'images/' で始まる場合は asset() を使う
                    // それ以外（Storageファサードでアップロードされたファイルなど）は Storage::url() を使う
                        $imageUrl = Str::startsWith($product->image, 'images/')
                                    ? asset($product->image)
                                    : Storage::url($product->image);
                    @endphp
                    <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="product-detail-image">
                @else
                    <img src="{{ asset('images/no-image.png') }}" alt="No Image" class="product-detail-image">
                @endif
                <div class="file-upload-area">
                    <label for="image" class="file-select-button">ファイルを選択</label>
                    <input type="file" id="image" name="image" accept="image/*" style="display: none;">
                    <span class="file-name">{{ $product->image ? basename($product->image) : 'image01.jpg' }}</span>
                </div>
                @error('image')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="product-info-section">
                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="product-form">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label for="name" class="form-label">商品名</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" class="form-input" required>
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="price" class="form-label">値段</label>
                        <input type="number" id="price" name="price" value="{{ old('price', $product->price) }}" class="form-input" required min="0" max="10000">
                        @error('price')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">季節</label>
                        <div class="checkbox-group">
                            @foreach($allSeasons as $season)
                                <input type="checkbox" id="season_{{ $season->id }}" name="seasons[]" value="{{ $season->id }}"
                                    {{ in_array($season->id, old('seasons', $product->seasons->pluck('id')->toArray())) ? 'checked' : '' }}>
                                <label for="season_{{ $season->id }}">{{ $season->name }}</label>
                            @endforeach
                        </div>
                        @error('seasons')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group description-group">
                        <label for="description" class="form-label">商品説明</label>
                        <textarea id="description" name="description" class="form-textarea" required>{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-buttons">
                        <a href="{{ route('products.index') }}" class="button back-button">戻る</a>
                        <button type="submit" class="button save-button">変更を保存</button>
                    </div>
                </form>
                <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('本当に削除しますか？');" class="delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="button delete-button"></button>
                </form>

            </div>
        </div>
    </main>
</body>
</html>