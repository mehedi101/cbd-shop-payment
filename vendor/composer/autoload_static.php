<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9363c4fb7e2585ac5b7cab18bbc59d64
{
    public static $files = array (
        'aef6589ca615b37256ce33685e82c48f' => __DIR__ . '/../..' . '/includes/functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'Cbd\\Shop\\' => 9,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Cbd\\Shop\\' => 
        array (
            0 => __DIR__ . '/../..' . '/includes',
        ),
    );

    public static $classMap = array (
        'Cbd\\Shop\\Admin' => __DIR__ . '/../..' . '/includes/Admin.php',
        'Cbd\\Shop\\Admin\\Menu' => __DIR__ . '/../..' . '/includes/Admin/Menu.php',
        'Cbd\\Shop\\Frontend' => __DIR__ . '/../..' . '/includes/Frontend.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9363c4fb7e2585ac5b7cab18bbc59d64::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9363c4fb7e2585ac5b7cab18bbc59d64::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit9363c4fb7e2585ac5b7cab18bbc59d64::$classMap;

        }, null, ClassLoader::class);
    }
}
