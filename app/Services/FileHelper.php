<?php

namespace App\Services;

class FileHelper
{
    /**
     * Gets all files and folders from directory
     * @param string $path
     * @param string $select
     * @return array|null
     *
     */
    public function get($path, $select = 'all')
    {
        try{
            $directory = $path;
            $result = [];

            if(is_file($path)){
                $result[] = $path;
                return $result;
            }

            $objects = array_diff(scandir($directory), ['..', '.']);

            foreach ($objects as $obj){
                $result_path = $directory.DIRECTORY_SEPARATOR.$obj;
                if($select == 'all'){
                    $result[] = $result_path;
                }
                elseif($select == 'files'){
                    if(is_file($result_path)){
                        $result[] = $result_path;
                    }
                }
                elseif($select == 'folders'){
                    if(is_dir($result_path)){
                        $result[] = $result_path;
                    }
                }
            }
            return $result;
        }
        catch (\Exception $e){
            return null;
        }
    }

    /**
     * Deletes file/files from directory. Can set 'white list' of files
     * @param string|array $files
     * @param array|string $except
     * @return array|null
     */
    public function delete($files, $except = null)
    {
        $result = [];
        try{
            if(is_array($files)){
                if(count($files)){
                    foreach ($files as $file) {
                        try{

                            if(file_exists($file)){

                                /*analyze, do we need to skip file*/
                                $file_extention = pathinfo($file)['extension'] ?? null;

                                if($file_extention){
                                    $file_extention = strtolower($file_extention);
                                }

                                if($except && $file_extention){
                                    if(is_array($except)){
                                        if(in_array($file_extention, $except)){
                                            $result[] = [
                                                'file' => $file,
                                                'status' => 'skipped',
                                            ];
                                            continue;
                                        }
                                    }
                                    else{
                                        if($file_extention == $except){
                                            $result[] = [
                                                'file' => $file,
                                                'status' => 'skipped',
                                            ];
                                            continue;
                                        }
                                    }
                                }
                                /*end analyze*/

                                unlink($file);
                                $status = file_exists($file);
                                $result[] = [
                                    'file' => $file,
                                    'status' => $status == true ? 'still_exists' : 'deleted',
                                ];
                            }
                            else{
                                $result[] = [
                                    'file' => $file,
                                    'status' => 'doesnt_exist'
                                ];
                            }
                        }
                        catch (\Exception $e){
                            $result[] = [
                                'file' => $file,
                                'status' => 'exception',
                                'description' => 'Message: '.$e->getMessage(),
                            ];
                        }
                    }
                    return $result;
                }
            }
            else{
                /*analyze, do we need to skip file*/
                $file_extention = pathinfo($files)['extension'] ?? null;

                if($file_extention){
                    $file_extention = strtolower($file_extention);
                }

                if($except && $file_extention){
                    if(is_array($except)){
                        if(in_array($file_extention, $except)){
                            $result[] = [
                                'file' => $files,
                                'status' => 'skipped',
                            ];
                            return $result;
                        }
                    }
                    else{
                        if($file_extention == $except){
                            $result[] = [
                                'file' => $files,
                                'status' => 'skipped',
                            ];
                            return $result;
                        }
                    }
                }
                /*end analyze*/

                unlink($files);

                $status = file_exists($files);

                $result[] = [
                    'file' => $files,
                    'status' => $status == true ? 'still_exists' : 'deleted',
                ];
            }
        }
        catch (\Exception $exception){
            return null;
        }
    }

    /**
     * Creates directory if one does not exists
     * @param string $path
     * @return string|boolean
     */
    public function createDirectory($path)
    {
        try{
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
            return $path;
        }
        catch (\Exception $exception){
            return false;
        }
    }

    /**
     * Creates image from binary
     * @param string $binary
     * @param string $final_name
     * @param string $output_ext
     * @return string|null
     */
    public function saveImageFromBinary($binary, $final_name = null, $output_ext = 'jpg')
    {
        try{
            //save img cover
            if($binary == null) return null;

            $ext = '.'.$output_ext;

            $im = imagecreatefromstring(base64_decode($binary));
            if($final_name == null){
                $img_name = str_random(32).$ext;
            }
            else{
                $img_name = $final_name.$ext;
            }

            $image_directory = public_path('images_');
            $this->createDirectory($image_directory);
            $temp_img = $image_directory.DIRECTORY_SEPARATOR.$img_name;

            if($output_ext == 'jpg'){
                $img = imagejpeg($im, $temp_img);
            }
            elseif($output_ext == 'png'){
                $img = imagepng($im, $temp_img);
            }

            imagedestroy($im);

            return $image_directory.DIRECTORY_SEPARATOR.$img_name;
        }
        catch(\Exception $e) {
            return null;
        }
        catch (\Throwable $e){
            return null;
        }
    }

    /**
     * Moves file to specified directory
     * @param string $file_origin
     * @param string $path
     * @param string $filename
     * @return string
     */
    public function move($file_origin, $path, $filename)
    {
        try{
            $this->createDirectory($path);
            rename($file_origin, $path.$filename);

            return $path.$filename;
        }
        catch (\Exception $e){
            return null;
        }
    }

    /**
     * Gets all file from directory
     * @param string $path
     * @return array|boolean
     */
    public function getAll($path)
    {
        if(!file_exists($path)){
            return false;
        }
        $directory = new \RecursiveDirectoryIterator($path);
        $iterator = new \RecursiveIteratorIterator($directory);
        $files = [];
        foreach ($iterator as $info) {
            if ($info->getFileName() != '.' && $info->getFileName() != '..') {
                $files[] = $info->getPathname();
            }
        }

        return $files;
    }

    /** Gets all directories from directory
     * @param $path
     * @return array
     */
    public function getAllDirectories($path)
    {
        $directories = glob($path . '/*' , GLOB_ONLYDIR);

        return $directories;
    }

    /**
     * Parses csv data from file
     * @param string $file
     * @param boolean $remove_header
     * @return array
     */

    public function parseCsv($file, $remove_header = false)
    {
        if(!is_string($file) || !file_exists($file)){
            return null;
        }

        $csv = array_map('str_getcsv', file($file));
        array_walk($csv, function(&$a) use ($csv) {
            $a = array_combine($csv[0], $a);
        });

        if($remove_header){
            array_shift($csv); # remove column header
        }

        return $csv;
    }

    /**
     * Save data into csv file
     * @param array $data
     * @param string $file_name
     * @param array $headers
     * @param boolean $need_headers
     * @return string|null
     */

    public function saveCsv($data, $file_name = null, $headers = [], $need_headers = false)
    {
        if($data){
            if(!$file_name){
                $file_name = str_random().'.csv';
            }

            $path = public_path($file_name);
            if(!file_exists($path)){
                $fp = fopen($file_name, 'w');

                if(!$headers && $need_headers){
                    $headers = array_values($data[0]);
                    fputcsv($fp, $headers);
                }

                foreach ($data as $fields) {
                    if(!is_array($fields)){
                        $fields = [$fields];
                    }
                    fputcsv($fp, $fields);
                }

                fclose($fp);
            }
            else{
                $fp = fopen($path, "a");
                foreach ($data as $fields) {
                    if(!is_array($fields)){
                        $fields = [$fields];
                    }
                    fputcsv($fp, $fields);
                }
//                fwrite($fp,$write);
                fclose($fp);
            }


            return $path;
        }

        return null;

    }



}