<?php
header('Contet-Type:application/xhtml+xml; charset=utf-8');
echo '<?xml version="1.0" encoding="UTF-8"?>';
?>
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML+RDFa 1.0//EN' 'http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd'>
<html xmlns='http://www.w3.org/1999/xhtml' xml:lang='he' dir='rtl'>
<head>
    <title><?php echo implode(' :: ',$this->titles);?></title>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8" />
    <meta name="description" content='<?php echo $this->description;?>' />
    <link rel="icon" href="<?php echo $this->base_path;?>images/favicon.png" />
    <?php
     if ($this->online):
        $src = $this->base_path . "min/b=" . $this->sub_path . "css&amp;f=";
        $sep='';
        foreach($this->css as $name){
            $src .=$sep.$name.'.css';
            $sep=',';
        }
    ?>
    <link rel='stylesheet' type='text/css' href='<?php echo $src;?>' />
    <?php else:?>
       <?php foreach($this->css as $name):?>
           <link rel='stylesheet' type='text/css' href='<?php echo $this->base_path . "css/$name.css";?>' />
       <?php endforeach;?>
    <?php endif;?>
    <!--[if IE]>
    <link rel='stylesheet' type='text/css' href='<?php echo $this->base_path;?>css/ie.css' />
    <![endif]-->
    <!--[if lt IE 8]>
    <link rel='stylesheet' type='text/css' href='<?php echo $this->base_path;?>css/mooie6update.css' />
    <![endif]  -->
    <!--[if lt IE 7]>
        <style type="text/css">
        img, div { behavior: url(<?php echo $this->base_path?>css/iepngfix.htc) }
        </style>
    <![endif]-->
    <base href="<?php echo $this->base_path;?>" />
</head>
<body>
<!--[if IE 6]>
<div id='ie6'>
<![endif]-->
<!--[if IE 7]>
<div id ='ie7'>
<![endif]-->
<!--[if IE 8]>
<div id='ie8'>
<![endif]-->

<div id='access_box'>
<a tabindex="1" class='access' href='#main'>דלג על שורת הניווט</a>
<a tabindex="2" href='<?php echo $this->base_path . 'sitemap.php'?>' class='access' rel='sitemap'>מפת האתר</a>
</div>
<div id = 'header'>
    <div class='wrapper'>
        <a id='logo' href='<?php echo $this->base_path;?>'>
            </a>
    </div>
    <?php echo $this->menu->generate();?>
</div>