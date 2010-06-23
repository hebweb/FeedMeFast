<h1>רשימת רכיבים</h1>
<div id='content'>
<ol>
<?php foreach ($this->model->getIngridiants() as $ing):?>
    <li>    	<dl>    		<dt>שם:</dt>    		<dd><?php echo $ing['name']?></dd>
    		
    		<dt>מתאים למתכונים חמים?</dt>
    		<dd><?php echo $ing['hot'] ? 'כן' : 'לא'?></dd>
    		
    		<dt>מתאים למתכונים קרים?</dt>
    		<dd><?php echo $ing['cold'] ? 'כן' : 'לא'?></dd>
    		
    		<?php if ($ing['manufactors']):?>
        		<dt>יצרנים:</dt>        		<?php foreach ($ing['manufactors'] as $manu):?>
        		  <dd><?php echo $manu['name'];?></dd>
        		<?php endforeach;?>
            <?php endif;?>    	</dl>    </li>
<?php endforeach;?>
</ol>
</div>