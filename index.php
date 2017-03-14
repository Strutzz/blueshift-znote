<?php 

define('ROOT_PATH', __DIR__);

require_once ROOT_PATH . '/engine/exceptions/LayoutNotFoundException.php';

class Layout 
{
    /**
     * Create a new Layout instance.
     *
     * @param string  $name
     * @return void
     */
    public function __construct($name) 
    {
        $this->name = $name;

        if (! $this->exists()) {
            throw new LayoutNotFoundException("File {$this->path('layout.php')}");
        }
    }

    /**
     * Determine if layout exists.
     *
     * @return boolean
     */
    public function exists()
    {
        return file_exists($this->path('layout.php'));
    }

    /**
     * Absolute path to theme. 
     *
     * @param string  $deeper
     * @return string
     */
    public function path($deeper = null)
    {
        if ($deeper) {
            return ROOT_PATH . "/layouts/{$this->name}/$deeper";
        }

        return ROOT_PATH . "/layouts/{$this->name}";
    }
}

$layout = new Layout('beastnn');

require_once 'engine/init.php'; 
// include 'layout/overall/header.php';
// include 'layout/overall/footer.php'; 


require_once $layout->path('layout.php');




