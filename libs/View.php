<?php

class View
{
  
  public $body;
  public $title;
  public $titleHead;
  public $href;
  public $layOut;
  public $pathMedia;
  
  public $js;
  public $css;
  public $imgLogo;
  public $logo;
  public $slogan;
 
  public function show($template, $vars = array())
  {
		$this->asignaValor($template, $vars);
		
		if ( is_file($this->layOut) === false )
    {
			die( 'Error no existe el LayOut!: ' . $this->layOut );
    }
    if ( is_file($this->body) === false )
    {
      die( 'No existe la vista ' . $this->body );
    }

    if(is_array($vars))
    {
      foreach ($vars as $key => $value)
      {
        $key = $value;
      }
    }

    $css = isset($vars['css']) ? $vars['css'] : "";

    if( !empty($css) )
    {
			$this->prepareStyleSheet($vars['css']);
    }

    $js = isset($vars['js']) ? $vars['js'] : "";

    if( !empty($js) )
    {
			$this->prepareScriptsJs($vars['js']);
    }

    include($this->layOut);
  }
  
  public function viewLogin($vars = array())
  {
		$this->asignaValor();
		include (Config::$configuration->get("layoutlogin"));
		die();
  }

  public function error404()
  {
    $config = Config::singleton();
    $path = Config::$configuration->get('pathlayout');
    $this->body = $path . 'error_404.php';
    include($this->body);
  }
  
  private function asignaValor($template = "", $vars = "")
  {
		$this->body = empty( $template ) ? Config::$configuration->get('viewsFolder') : Config::$configuration->get('viewsFolder') . $template;
		$this->layOut = empty($this->layOut) ? Config::$configuration->get('pathlayout') . Config::$configuration->get('layout') : $this->layOut;
		$this->title = isset($vars['title']) ? $vars['title'] : "";
		$this->titleHead = isset($vars['title_head']) ? $vars['title_head'] : $this->title;
		$this->href = isset($vars['href']) ? 'href=' .'"'. $vars['href'] .'"' : "";
		$this->hrefTitle = isset($vars['href_title']) ? $vars['href_title'] : "";
		$this->pathMedia = Config::$configuration->get('media');
		$this->logo = isset($this->logo) ? $this->logo : Config::$configuration->get('logo');
		$this->imgLogo = isset($this->imgLogo) ? $this->imgLogo : Config::$configuration->get('img_logo');
		$this->slogan = isset($this->slogan) ? $this->slogan : Config::$configuration->get('slogan');
  }
  
  private function prepareScriptsJs($scripts)
  {
		foreach( $scripts as $script )
		{
			if( file_exists(PROJECTPATH . '/media/'. $script) === true )
			{
				$this->js .= '<script type="text/javascript" src="'. $this->pathMedia . $script .'"></script>' . PHP_EOL;
			}
			else 
			{
				$this->js .= '';
			}
		}
  }
  
  private function prepareStyleSheet($styleSheets)
  {
		foreach( $styleSheets as $styleSheet )
		{
			if( file_exists(PROJECTPATH . '/media/'. $styleSheet) === true )
			{
				$this->css .= '<link rel="stylesheet" href="'. $this->pathMedia . $styleSheet .'">' . PHP_EOL;
			}
			else 
			{
				$this->css .= '';
			}
		}
  }
}
