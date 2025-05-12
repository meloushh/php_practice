<?php

namespace Framework;

require_once 'functions.php';
use Exception;

class HtmlEngine {
    static function Render(string $path, array $data, bool $no_output = false) {
        $notification = '';
        if (isset($_COOKIE['notification'])) {
            $notification = $_COOKIE['notification'];
            unset($_COOKIE['notification']);
            App::$si->DeleteCookie('notification');
        }

        if (ob_start() === false) 
            throw new Exception('ob_start failed');
        
        extract($data);
        unset($data);
        
        require $path;
        $page = ob_get_contents();
        ob_clean();

        $output = '';
        if (strpos($page, '@extends') !== false) {
            $template_path = GetStringBetween($page, "@extends('", "')");

            require BASE_DIR.$template_path;
            $output = ob_get_contents();
            ob_clean();
        }

        $sections = [];
        $offset = 0;
        while (($keyword_start_i = strpos($page, '@section_start', $offset)) !== false) {
            $keyword_end_i = strpos_exception($page, ")", $keyword_start_i) + 1;
            
            $keyword = substr($page, $keyword_start_i, $keyword_end_i - $keyword_start_i);
            $name = GetStringBetween($keyword, "@section_start('", "')");

            $section_end_i = strpos_exception($page, '@section_end', $keyword_end_i);

            $section_start_i = $keyword_end_i + 1;

            $sections[$name] = trim(substr($page, $section_start_i, $section_end_i - $section_start_i));

            $offset = $section_end_i + strlen('@section_end');
        }

        foreach ($sections as $name => $content) {
            $search = "@section('{$name}')";
            $output = str_replace($search, $content, $output);
        }

        if ($no_output) {
            return $output;
        } else {
            echo $output;
        }
    }
}

?>