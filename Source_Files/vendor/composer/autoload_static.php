<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit7659c53c88ba7f8c0c9dc9b6ac6ee997
{
    public static $prefixLengthsPsr4 = array (
        'E' => 
        array (
            'Eihror\\Compress\\' => 16,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Eihror\\Compress\\' => 
        array (
            0 => __DIR__ . '/..' . '/eihror/compress-image/src',
        ),
    );

    public static $classMap = array (
        'Eihror\\Compress\\Compress' => __DIR__ . '/..' . '/eihror/compress-image/src/Compress.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit7659c53c88ba7f8c0c9dc9b6ac6ee997::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit7659c53c88ba7f8c0c9dc9b6ac6ee997::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit7659c53c88ba7f8c0c9dc9b6ac6ee997::$classMap;

        }, null, ClassLoader::class);
    }
}
