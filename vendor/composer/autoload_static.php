<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit442a1845a0c128e679eda576f1aaeb99
{
    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->classMap = ComposerStaticInit442a1845a0c128e679eda576f1aaeb99::$classMap;

        }, null, ClassLoader::class);
    }
}