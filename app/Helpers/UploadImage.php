<?php
namespace App\Helpers;

class UploadImage {

    public static function uploadFile ($key, $path) {
        
        request()->file($key)->store($path);    //  sube el archivo y lo guarda en el directorio especificado
        return request()->file($key)->hashName();   //  retornamos el nombre con el que ha guardado el archivo
    }
}