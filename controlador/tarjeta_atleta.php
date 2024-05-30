<?php

  if(is_file("vista/".$p.".php")){
	  require_once("vista/".$p.".php"); 
  }
  else{
	  require_once("comunes/404.php"); 
  }

?>