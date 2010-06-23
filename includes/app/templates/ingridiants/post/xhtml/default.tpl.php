<?php
if ($this->model->isError()){
  echo "<h1>Errors:</h1>";
  var_dump($this->model->getErrors());
}
else{
  echo "<h1>Success</h1>";
  var_dump($this->model->getIngridiant());
}
?>
<a href='ingridiants/add'>חזור</a>