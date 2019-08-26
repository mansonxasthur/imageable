<?php
/**
 * User: Manson
 * Date: 6/27/2018
 * Time: 2:54 PM
 * Update: 3/14/2019
 */
namespace MX13\Imageable\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

trait Imageable
{
    /**
     * Stores image in storage/app/public/img
     * and returns hashed file name
     *
     * @param UploadedFile $file
     * @param string $format
     * @return string
     */
    public static function uploadImage( UploadedFile $file, string $format = null ): string
    {
        $format ?: config('imageable.format');

        return self::optimizeImage( $file, $format );
    }

    /**
     * Replace an existing image with a new one
     * and return the file name of the new image
     *
     * @param UploadedFile $file
     * @param string $oldFileName
     *
     * @param null $format
     * @return string
     * @throws \Exception
     */
    public static function updateImage( UploadedFile $file, ?string $oldFileName, $format = null ): string
    {
        $format ?: config('imageable.format');

        if ($oldFileName) {
            if ( Storage::disk( config( 'filesystems.default' ) )->exists( $oldFileName ) ) {
                if ( self::deleteImage( $oldFileName ) ) {
                    return self::optimizeImage( $file, $format );
                }
                throw new \Exception('Failed to delete old image');
            }
        }

        return self::uploadImage( $file );
    }

    /**
     * Delete image from storage/app/public/img
     *
     * @param string $fileName
     *
     * @return bool
     */
    public static function deleteImage( string $fileName ): bool
    {
        if ( Storage::disk( config( 'filesystems.default' ) )->exists( $fileName ) ) {
            return Storage::delete( $fileName );
        }
        return false;
    }

    /**
     * Return an http response with the raw file
     *
     * @param string $fileName
     *
     * @return mixed
     */
    public function downloadImage( string $fileName )
    {
        return Storage::download( $fileName );
    }

    /**
     * Get the full image url
     *
     * @param string $fileName
     *
     * @return string
     */
    public static function getImageUrl( ?string $fileName ): string
    {
        return $fileName ? Storage::url( $fileName ) : '';
    }

    /**
     * Optimize image size and optionally formatting it
     *
     * @param UploadedFile $file
     * @param string|null $format
     *
     * @return string
     */
    protected static function optimizeImage( UploadedFile $file, ?string $format ): string
    {
        $extension = $format ?? $file->getClientOriginalExtension();
        return self::storeImage( Image::make( $file->getRealPath() )->encode( $extension ), $extension );
    }

    /**
     * @param $image
     * @param string $extension
     *
     * @return string
     */
    protected static function storeImage( $image, $extension ): string
    {
        $fileName = Str::random( 40 ) . '.' . $extension;
        $path = storage_path( 'app/public/' . config('imageable.path') . '/' . $fileName );
        $image->save( $path );
        return config('imageable.path') . '/' . $fileName;
    }
}