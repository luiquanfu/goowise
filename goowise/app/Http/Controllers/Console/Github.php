<?php

namespace App\Http\Controllers\Console;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class Github extends Controller
{
    public function index()
    {
        $local_path = 'C:\xampp\htdocs\goowise';
        $server_path = 'C:\xampp\htdocs\goowise_www';

        // delete everything inside server_path
        if(is_dir($server_path))
        {
            $this->delete_files($server_path);
        }
        mkdir($server_path);

        $data = file_get_contents(storage_path().'/github_diff.txt');
		$lines = explode("\n", $data);
		for($i = 0; $i < count($lines); $i++)
		{
            // ignore first line
            if($i == 0)
            {
                continue;
            }

            // set variables
            $index_filename = 3;
            $line = trim($lines[$i]);
            $words = explode(' ', $line);
            if($words[0] == 'BIN')
            {
                $index_filename = 2;
            }
            $filename = trim($words[$index_filename]);
			$from = $local_path.'/'.$filename;
            $to = $server_path.'/'.$filename;
            \Log::info($from);

			// ignore env file
			if(strpos($filename, '.env') !== false)
			{
				if(strpos($filename, 'offline') === false)
				{
					$this->info($from.' (IGNORE)');
					continue;
				}
            }
            
            // create directory folder
			$path = pathinfo($to);
			if(!file_exists($path['dirname']))
			{
				mkdir($path['dirname'], 0777, true);
			}

			// copy file
            copy($from, $to);
        }
    }

    public function delete_files($dir)
    {
        foreach(glob($dir . '/*') as $file)
        {
            if(is_dir($file))
            {
                $this->delete_files($file);
            }
            else
            {
                unlink($file);
            }
        }
        rmdir($dir);
    }
}
