<?php

class template {
    public $path;
    public $content;

    public function __construct($file) {
        $this->path = $file;
        $this->template();
    }

    public function template() {
        if (file_exists($this->path)) {
            $file = fopen($this->path, "r");
        } else {
            die ($this->path." nicht vorhanden");
        }
        while(!feof($file)) {
            $temp = fgets($file, 4096);
            $this->content .= $temp;
        }
    }

    public function replace($title, $value) {
        $this->content = str_replace("{" . $title . "}", $value, $this->content);
    }

    public function echo_template() {
        return $this->content;
    }
}
?>