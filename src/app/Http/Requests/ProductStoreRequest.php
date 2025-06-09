<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'price' => 'required|integer|min:0|max:10000',
            'seasons' => 'required|array',
            'seasons.*' => 'integer|exists:seasons,id',
            'description' => 'required|string|max:120',
            'image' => 'required|image|mimes:png,jpeg|max:2048',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'price.required' => '値段を入力してください',
            'price.integer' => '数値で入力してください',
            'price.min' => '0~10000円以内で入力してください',
            'price.max' => '0~10000円以内で入力してください',
            'seasons.required' => '季節を選択してください',
            'seasons.array' => '季節を選択してください', 
            'seasons.*.integer' => '季節の選択が正しくありません',
            'seasons.*.exists' => '選択された季節が正しくありません',
            'description.required' => '商品説明を入力してください',
            'description.max' => '120文字以内で入力してください',
            'image.required' => '商品画像を登録してください',
            'image.image' => '商品画像を登録してください', 
            'image.mimes' => '「.png」または「.jpeg」形式でアップロードしてください',
            'image.max' => '商品画像のサイズは2MB以内である必要があります。',
        ];
    }

}
