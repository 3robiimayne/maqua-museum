<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInite8682a7bfd1917e498c3f682e8b4d091
{
    public static $prefixLengthsPsr4 = array (
        'P' => 
        array (
            'PHPMailer\\PHPMailer\\' => 20,
        ),
        'I' => 
        array (
            'Ismail\\Maqua\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'PHPMailer\\PHPMailer\\' => 
        array (
            0 => __DIR__ . '/..' . '/phpmailer/phpmailer/src',
        ),
        'Ismail\\Maqua\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInite8682a7bfd1917e498c3f682e8b4d091::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInite8682a7bfd1917e498c3f682e8b4d091::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInite8682a7bfd1917e498c3f682e8b4d091::$classMap;

        }, null, ClassLoader::class);
    }
}
