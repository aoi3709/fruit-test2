<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductUpdateRequest;
use App\Http\Requests\ProductStoreRequest;
use App\Models\Product;
use App\Models\Season;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
      
   /**
     * 商品一覧を表示
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Product::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where('name', 'like', '%' . $keyword . '%')
                  ->orWhere('description', 'like', "%{$keyword}%");
        }

        if ($request->filled('sort')) {
            $sort = $request->sort;
            if ($sort == 'price_asc') {
                $query->orderBy('price', 'asc');
            } elseif ($sort == 'price_desc') {
                $query->orderBy('price', 'desc');
            }
        } else {
            $query->orderBy('id', 'desc');
        }

        $products = $query->with('seasons')->paginate(6); 

        return view('products.index', compact('products')); 
    }

    /**
     * 商品詳細画面を表示
     * GET /products/{productId}
     *
     * @param  \App\Models\Product  $product 
     * @return \Illuminate\View\View
     */
    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    /**
     * 商品登録画面を表示
     * GET /products/create
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $seasons = Season::all();
        return view('products.create', compact('seasons'));
    }

    /**
     * 商品をデータベースに保存
     * POST /products
     *
     * @param  \App\Http\Requests\ProductStoreRequest  $request 
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ProductStoreRequest $request)
    {
        
        $validatedData = $request->validated();

        $imagePath = null; 
        if ($request->hasFile('image')) {
            $uploadedPath = $request->file('image')->store('products', 'public');
            $imagePath = $uploadedPath;
        }

        $product = Product::create([
            'name' => $validatedData['name'],
            'price' => $validatedData['price'],
            'description' => $validatedData['description'],
            'image' => $imagePath,
        ]);

        $product->seasons()->sync($request->input('seasons'));

        return redirect()->route('products.index')->with('success', '商品を登録しました。');
    
    }

    /**
     * 商品更新画面を表示
     * GET /products/{product}/edit
     *
     * @param  \App\Models\Product  $product 
     * @return \Illuminate\View\View
     */
    public function edit(Product $product) 
    {
        $allSeasons = Season::all();
        return view('products.edit', compact('product', 'allSeasons'));
    }

    /**
     * 指定された商品をデータベースで更新。
     * PUT/PATCH /products/{product}
     * 
     * @param  \App\Http\Requests\ProductUpdateRequest  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProductUpdateRequest $request, Product $product)
    {
        $validatedData = $request->validated();

        if ($request->hasFile('image')) {
            
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            $imagePath = $request->file('image')->store('products', 'public');
            $validatedData['image'] = $imagePath;
        } else {
            
        }

        $product->name = $validatedData['name'];
        $product->price = $validatedData['price'];
        $product->description = $validatedData['description'];

        $product->fill($validatedData);

        if ($request->hasFile('image')) {
             $product->image = $imagePath;
        }
        
        $product->save();

        $product->seasons()->sync($request->input('seasons', []));

        return redirect()->route('products.index')->with('success', '商品を更新しました。');
    }

    /**
     * 指定された商品をデータベースから削除。
     * DELETE /products/{product}
     * 
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', '商品を削除しました。');
    }


}
