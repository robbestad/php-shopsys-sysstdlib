<?php

namespace SysStdLib;

class SysStdLib
{
    /*
    * Select the correct config file.
    * sysconfig.local.php is the preferred config file,
    * followed by sysconfig.global.php
    * and sysconfig.global.php.dist
    */
    public static function getConfig($module = '', $dir = __DIR__)
    {
    	if(empty($module)) throw new \Exception("Error Processing Request", 1);

        $configFilesArrayMap = [];
        $folders = array(
            $dir . "/../config/",
            $dir . "/../../config/",
            $dir . "/../../../".$module."/config/",
            $dir . "/../../../../config/autoload/",
            $dir . "/../../../../../config/autoload/",
        );
        foreach ($folders as $folder) {
            $fileMap = [];
            if (is_dir($folder)) {
                $fileMap[] = preg_grep('~^'.strtolower($module).'.*\.(dist|local|php)$~', scandir($folder));
                foreach ($fileMap as $fileArray) {
                    foreach ($fileArray as $file) {
                        $configFilesArrayMap[] = $folder . $file;

                    }
                }
            }
        }

        $configFilesArray = [];
        $a=[];

        //find files by rank order
        $findFiles = preg_grep("/local.php.*/", $configFilesArrayMap);
        if (!empty($findFiles)) {
            foreach ($findFiles as $file) {
                $configFilesArray[] = $file;
                $a[]=include $file;
            }
        }
        $findFiles = preg_grep("/global.php$/", $configFilesArrayMap);
        if (!empty($findFiles)) {
            foreach ($findFiles as $file) {
                $configFilesArray[] = $file;
                $a[]=include $file;
            }
        }

        $findFiles = preg_grep("/php.dist$/", $configFilesArrayMap);
        if (!empty($findFiles)) {
            foreach ($findFiles as $file) {
                $configFilesArray[] = $file;
                $a[]=include $file;
            }
        }

//        $config=self::array_filter_recursive($a);
        $config=self::merge($a,$a);
        $i=count($config);
        $holdover=$out=[];
        while($i>0){
            if(!empty($config[$i])){
                if(!empty($holdover)){
                    $out=self::merge($config[$i],$holdover[key($out)]);
                    $out=self::merge($config[$i-1],$out);
                }
                else
                    $out=self::merge($config[$i],$config[$i-1]);
            }
            $i--;
        }


//        echo("\n\nout");
//        var_dump($out);
//        echo("out done");

        return $out;
    }

    /**
     * Merge two arrays together.
     *
     * If an integer key exists in both arrays and preserveNumericKeys is false, the value
     * from the second array will be appended to the first array. If both values are arrays, they
     * are merged together, else the value of the second array overwrites the one of the first array.
     *
     * @param  array $a
     * @param  array $b
     * @param  bool  $preserveNumericKeys
     * @return array
     */
    public static function merge(array $a, array $b, $preserveNumericKeys = false)
    {
        foreach ($b as $key => $value) {
            if (array_key_exists($key, $a)) {
                if (is_int($key) && !$preserveNumericKeys) {
                    $a[] = $value;
                } elseif (is_array($value) && is_array($a[$key])) {
                    $a[$key] = static::merge($a[$key], $value, $preserveNumericKeys);
                } else {
                    $a[$key] = $value;
                }
            } else {
                $a[$key] = $value;
            }
        }

        return $a;
    }

    /**
     * Run array_filter recursively through a multi dimensional array
     *
     * @param  array $input
     * @return array
     */
    public static function array_filter_recursive($input=[]){
        foreach ($input as &$value)
        {
            if (is_array($value))
            {
                $value = self::array_filter_recursive($value);
            }
        }
        return array_filter($input);
    }

}
