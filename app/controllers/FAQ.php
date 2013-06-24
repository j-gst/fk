<?php
/**
 * Controller FAQ
 * Dieser Controller ruft nur das passende Template auf
 * @author: Maike Schroeder
 */
namespace controllers;
class FAQ extends Controller
{
   public function run(){
	   $this->display("faq"); 
   }
}// class