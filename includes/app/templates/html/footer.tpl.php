<div id='footer'>
    
</div>
<!-- [if IE]>
</div>
<![endif]-->
<script type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/mootools/1.2.4/mootools-yui-compressed.js'></script>
<!--[if lt IE 8]>
    <script type='text/javascript' src='<?php echo $this->base_path;?>js/IENotifier.js'></script>
<![endif]-->
<?php
if ($this->online):
$src = $this->base_path . "min/b=" . $this->sub_path . "js&amp;f=";
$sep='';
foreach($this->js as $name){
    $src .=$sep.$name.'.js';
    $sep=',';
}?>
<script type='text/javascript' src='<?php echo $src;?>'></script>
<?php else:?>
    <?php foreach ($this->js as $name):?>
        <script type='text/javascript' src='<?php echo $this->base_path . "js/$name.js";?>'></script>
    <?php endforeach;?>
<?php endif;?>
<?php if (isset($this->album)):
    $list = "<ul class='thumbs-list'>";
    ?>
<script id='album-content' type='text/javascript'>
//<![CDATA[
/*
<?php foreach ($this->album as $image):
    $li = "<li class='thumb'><a href='{$image->source}' class='smoothbox'><img src='{$image->url}' height='{$image->height}' width='{$image->width}' alt='' /></a></li>";
    $list.=$li;
    echo htmlentities($li);
endforeach;?>
*/
//]]>
</script>
<?php
$list .="</ul>";
?>
<noscript>
<?php echo $list;?>
</noscript>
<?php endif;?>
<script type='text/javascript'>
//<![CDATA[
   <?php echo "var base_path = '{$this->base_path}';\n"?>
//]]>
</script>
</body>
</html>