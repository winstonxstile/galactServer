<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Image;
use Illuminate\Support\Facades\File;


class ImageController extends Controller
{
    // Метод для добавления изображения
    public function addImage(Request $request)
    {
        // Проверяем, есть ли файл или URL в запросе
        if ($request->hasFile('image')) {
            // Получаем файл из запроса
            $file = $request->file('image');
            // Генерируем уникальное имя для файла
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            // Сохраняем файл в публичной папке storage
            $file->storeAs('public/images', $filename);
            // Создаем новую запись в базе данных с именем файла и путем к нему
            $image = new Image();
            $image->name = $filename;
            $image->path = 'storage/images/' . $filename;
            $image->save();
        } elseif ($request->has('url')) {
            // Получаем URL из запроса
            $url = $request->input('url');
            // Проверяем, является ли URL действительным
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                // Получаем содержимое URL
                $content = file_get_contents($url);
                // Проверяем, является ли содержимое изображением
                if (@imagecreatefromstring($content)) {
                    // Генерируем уникальное имя для файла
                    $filename = uniqid() . '.jpg';
                    // Сохраняем файл в публичной папке storage
                    Storage::put('public/images/' . $filename, $content);
                    // Создаем новую запись в базе данных с именем файла и путем к нему
                    $image = new Image();
                    $image->name = $filename;
                    $image->path = 'storage/images/' . $filename;
                    $image->save();
                } else {
                    // Возвращаем ошибку, если содержимое не является изображением
                    return response()->json(['error' => 'Invalid image URL'], 400);
                }
            } else {
                // Возвращаем ошибку, если URL не является действительным
                return response()->json(['error' => 'Invalid URL'], 400);
            }
        } else {
            // Возвращаем ошибку, если нет файла или URL в запросе
            return response()->json(['error' => 'No image file or URL provided'], 400);
        }

        // Возвращаем успешный ответ с данными о загруженном изображении
        return response()->json(['success' => 'Image uploaded successfully', 'image' => $image], 200);
    }

    public function getImages(Request $request)
    {
        // Получаем имя файла из параметра запроса
        $filename = $request->input('filename');
        // Проверяем, существует ли файл в папке storage/app/public/images
        if (Storage::disk('public')->exists('images/' . $filename)) {
            // Возвращаем файл изображения с помощью response()->file()
            return response()->file(storage_path('app/public/images/' . $filename));
        } else {
            // Возвращаем ответ с кодом 404, если файл не найден
            return response()->json(['message' => 'File not found'], 404);
        }
    }

    public function destroy(Request $request){
        $filename = $request->input('imageName');
        if (Storage::disk('public')->exists('images/' . $filename)) {
            $image = Image::where('name', $filename)->firstOrFail();
            File::delete(storage_path('app/public/images/' . $filename));
            $image->delete();
        } else {
            // Возвращаем ответ с кодом 404, если файл не найден
            return response()->json(['message' => 'File not found'], 404);
        }


    }


    public function getAllImagesNames()
    {
        // Получаем все файлы с изображениями из папки storage
            $imageNames = Image::all()->pluck('name')->toArray();
            return response()->json($imageNames);
    }


    public function getAllImages(){
        $url = 'http://127.0.0.1:8000/api/getImages?filename=/';

        $images = Image::all()->pluck('name')->toArray();
        $imageUrls = [];

        foreach ($images as $image) {
            $imageUrls[] = $url.$image;
        }
        return response()->json($imageUrls);
    }
}
