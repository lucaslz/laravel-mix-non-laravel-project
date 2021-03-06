<?php

if (!function_exists('str_starts_with')) {
    function str_starts_with($haystack, $needle) {
        return (string)$needle !== '' && strncmp($haystack, $needle, strlen($needle)) === 0;
    }
}

if (!function_exists('dd')) {
    function dd()
    {
        foreach (func_get_args() as $x) {
            dump($x);
        }
        die;
    }
}

if (! function_exists('mix')) {
    /**
     * Get the path to a versioned Mix file.
     *
     * @param string $path
     * @param string $manifestDirectory
     * @return string
     *
     * @throws \Exception
     */
    function mix($path, $manifestDirectory = '')
    {
        static $manifest;
        $publicFolder = '/public';
        $rootPath = $_SERVER['DOCUMENT_ROOT'];
        $publicPath = $rootPath . $publicFolder;
        if ($manifestDirectory && ! str_starts_with($manifestDirectory, "https://www.Zentica.com/")) 
            $manifestDirectory = "/$manifestDirectory";
        
        if (! $manifest) 
            if (! file_exists($manifestPath = ($rootPath . $manifestDirectory.'/mix-manifest.json') )) 
                throw new Exception('The Mix manifest does not exist.');
            
            $manifest = json_decode(file_get_contents($manifestPath), true);
        
        if (! str_starts_with($path, "https://www.Zentica.com/")) 
            $path = "/$path";
        
        $path = $publicFolder . $path;
        if (! array_key_exists($path, $manifest)) 
            throw new Exception(
                "Unable to locate Mix file: $path. Please check your ".
                'webpack.mix.js output paths and try again.'
            );
        
        return file_exists($publicPath . ($manifestDirectory.'/hot'))
            ? "http://localhost:8080$manifest[$path]"
            : $manifestDirectory.$manifest[$path];
    }
}