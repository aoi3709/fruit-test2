<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>新規商品登録</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}"> <link rel="stylesheet" href="{{ asset('css/create.css') }}">
</head>
<body>
    <header class="header">
        <div class="header__inner">
            <a class="header__logo" href="{{ route('products.index') }}">
                mogitate
            </a>
        </div>
    </header>

    <main class="product-create-container">
        <h1 class="page-title">商品登録</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data" class="product-form">
            @csrf
            <div class="form-group">
                <label for="name" class="form-label">
                    商品名
                    <span class="required-tag">必須</span>
                </label>
                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="商品名を入力" class="form-input">
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="price" class="form-label">
                    値段
                    <span class="required-tag">必須</span>
                </label>
                <input type="number" id="price" name="price" value="{{ old('price') }}" min="0" max="10000" placeholder="値段を入力" class="form-input">
                @error('price')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="image" class="form-label">
                    商品画像
                    <span class="required-tag">必須</span>
                </label>
                <div class="file-upload-area">
                    <label for="image_upload_input" class="file-select-button">ファイルを選択</label>
                    <input type="file" id="image_upload_input" name="image" accept="image/*" style="display: none;">
                    <span id="file-name-display" class="file-name">選択されていません</span>
                </div>
                <small class="file-format-info">（.png, .jpeg, .jpg 形式）</small>
                @error('image')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    季節
                    <span class="required-tag">必須</span>
                    <span class="multiple-select-info">複数選択可</span>
                </label>
                <div class="checkbox-group"> 
                    @foreach($seasons as $season)
                        <div class="checkbox-item">
                            <input type="checkbox" id="season_{{ $season->id }}" name="seasons[]" value="{{ $season->id }}"
                    {{-- old('seasons', []) を使って、エラー時に選択が保持されるようにする --}}
                    {{ in_array($season->id, old('seasons', [])) ? 'checked' : '' }}>
                            <label for="season_{{ $season->id }}">{{ $season->name }}</label>
                        </div>
                    @endforeach
                </div>
                @error('seasons')
                   <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label for="description" class="form-label">
                    商品説明
                    <span class="required-tag">必須</span>
                </label>
                <textarea id="description" name="description" placeholder="商品の説明を入力" class="form-textarea">{{ old('description') }}</textarea>
                @error('description')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-actions">
                <a href="{{ route('products.index') }}" class="button back-button">戻る</a>
                <button type="submit" class="button register-button">登録</button>
            </div>
        </form>
    </main>

    <script>
        document.getElementById('image_upload_input').addEventListener('change', function(e) {
            const fileNameDisplay = document.getElementById('file-name-display');
            if (e.target.files.length > 0) {
                fileNameDisplay.textContent = e.target.files[0].name;
            } else {
                fileNameDisplay.textContent = '選択されていません';
            }
        });
    </script>
</body>
</html>