<!DOCTYPE html>
<html lang="en">
<head>
  <?php 
  if(isset($title_full)){
    echo '<title>' . h($title_full) . '</title>';
  }
  else {
    if(isset($page_title))
      $title_for_layout = $page_title;
    echo '<title>' . (isset($title_for_layout)? h($title_for_layout) .' | ' : '') .'Biodex</title>';
  }
  ?>
  
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
  
  <?php 
  echo $this->Html->css('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css');
  echo $this->Html->css('/css/main.css');
  echo $this->Html->css('/css/jquery-jvectormap-2.0.4.css');
  ?>
  
  <link href='https://fonts.googleapis.com/css?family=Roboto:300,700' rel='stylesheet' type='text/css'>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
  <script type="text/javascript" src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
  <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
  
  <?php $js_includes = (isset($js_includes) && is_array($js_includes))? $js_includes : array();
    foreach($js_includes as $js)
      echo '<script type="text/javascript" src="'.$js.'"></script>' . "\n";
      
  ?>
  
  <script src="/js/jquery-jvectormap-2.0.4.min.js"></script>
  <script src="/js/jquery-jvectormap-world-mill-en.js"></script>
  <script src="/js/jquery-jvectormap-us-merc-en.js"></script>
  <script type="text/javascript" src="/js/main.js"></script>
  <script type="text/javascript" src="/js/sorttable.js"></script>
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <?php
    $inline_css = $this->fetch('css');
		if(strlen($inline_css)){
      ?>
      <style type="text/css">
      <?=$inline_css?>
      </style>
		  <?
		}
		?>
		
		
		<script type="text/javascript">
      // Debugging support
      if(!window.console) console = {};
      console.log = console.log || function(){};
      console.error = console.error || function(){};

      var base_path  = <?=json_encode($base_path)?>; 
    </script>
    
    <?php
    $inline_js = $this->fetch('script');
		if(strlen($inline_js)){
      ?>
      <script type="text/javascript">
      <?=$inline_js?>
      </script>
		  <?
		}
		?>
		
    
</head>

<body>
    
    <!-- Navbar -->
    <nav class="navbar navbar-default">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="/">
            <img alt="Brand" src="/img/logo4.jpg" width=30>
          </a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="/search">Home</a></li>
            <li><a href="/search/search">Search</a></li>
            <li><a href="/search/browse">Browse</a></li>
            <li><a href="/search/stats">Stats</a></li>
            <li><a href="/search/gallery">Gallery</a></li>
            <li><a href="/search/article_work">Article Info</a></li>
            <?php if($is_logged_in){ ?>
            <li><a href="/login/logout">Log Out</a></li>
            <?php } ?>
          </ul>
        </div>  <!--/.nav-collapse -->
      </div>
    </nav>
    
    <div class="container" id="page-wrapper">
      
      <?php echo $this->Session->flash(); ?>
    
    	<?php echo $this->fetch('content'); ?>
      
    </div> <!-- /container -->

    <footer class="footer">
      <div class="container">
        
      </div>
    </footer>

    
  </body>
</html>
