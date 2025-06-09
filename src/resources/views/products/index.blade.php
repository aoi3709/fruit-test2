<!DOCTYPE html>
<html lang="ja">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>@if(request('keyword')) "{{ request('keyword') }}"の商品一覧 @else 商品一覧 @endif</title>
  <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
  <link rel="stylesheet" href="{{ asset('css/index.css') }}" />
</head>

<body>
  @php
    use Illuminate\Support\Str; 
  @endphp
  <header class="header">
    <div class="header__inner">
      <a class="header__logo" href="{{ route('products.index') }}">
        mogitate
      </a>
      <a class="add-product-button" href="{{ route('products.create') }}">
        + 商品を追加
      </a>
    </div>
  </header>

  <main>
    <div class="main-content-wrapper">
      <aside class="sidebar">
        <div class="sidebar__section">
        {{-- <div class="sidebar__heading">検索</div> --}}
          <form action="{{ route('products.index') }}" method="GET" class="form-group">
            <input type="text" name="keyword" placeholder="商品名を入力" value="{{ request('keyword') }}">
            <button type="submit" class="search-button">検索</button>
            @if(request('sort'))
                <input type="hidden" name="sort" value="{{ request('sort') }}">
            @endif
          </form>
        </div>

        <div class="sidebar__section">
          <div class="sidebar__heading">価格順で表示</div>
          <form action="{{ route('products.index') }}" method="GET" class="form-group">
            <select name="sort" onchange="this.form.submit()">
              <option value="">価格で並べ替え</option>
              <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>低い順に表示</option>
              <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>高い順に表示</option>
            </select>
            @if(request('keyword'))
                <input type="hidden" name="keyword" value="{{ request('keyword') }}">
            @endif
          </form>
        </div>
      </aside>

      <div class="main-content">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <h2 class="product-list__heading">
          @if(request('keyword'))
            "{{ request('keyword') }}"の商品一覧
          @else
            商品一覧
          @endif
        </h2>

        <div class="sort-tags-area">
        @if (request()->filled('keyword') || request()->filled('sort'))
            @if (request()->filled('keyword'))
                <div class="sort-tag">
                    <span>検索キーワード: **{{ request('keyword') }}**</span>
                    <a href="{{ route('products.index', \Illuminate\Support\Arr::except(request()->query(), 'keyword')) }}" class="clear-tag-button">×</a>
                </div>
            @endif
            @if (request()->filled('sort'))
                <div class="sort-tag">
                    <span>
                        並び替え:
                        @if (request('sort') == 'price_asc')
                            **価格：低い順に表示**
                        @elseif (request('sort') == 'price_desc')
                            **価格：高い順に表示**
                        @else
                            **選択してください**
                        @endif
                    </span>
                    <a href="{{ route('products.index', \Illuminate\Support\Arr::except(request()->query(), 'sort')) }}" class="clear-tag-button">×</a>
                </div>
            @endif
          @endif
        </div>

        <div class="product-cards">
          @forelse($products as $product)
          <a href="{{ route('products.edit', $product->id) }}" class="product-card-info-link">
              <div class="product-card">
                  @if ($product->image)
                      @php
                          $imageUrl = Str::startsWith($product->image, 'images/')
                                      ? asset($product->image)
                                      : Storage::url($product->image);
                       @endphp
                       <img src="{{ $imageUrl }}" alt="{{ $product->name }}" class="product-image">
                  @else
                       <img src="{{ asset('images/no-image.png') }}" alt="No Image" class="product-image">
                  @endif

                      {{-- <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="product-image">
                      <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="product-image">
                      @else
                      <img src="{{ asset('images/no-image.png') }}" alt="No Image" class="product-image">
                      @endif  --}}
                  <div class="product-info">
                      <p>{{ $product->name }}</p>
                      <p>¥{{ number_format($product->price) }}</p>
                  </div>
              </div>
          </a>

          @empty
          <p class="no-products-message">商品が見つかりませんでした。</p>
          @endforelse
        </div>

        <div class="pagination">
          {{ $products->appends(request()->query())->links() }}
        </div>
      </div>
    </div>
  </main>

</body>

</html>
