<?php
/**
 * Controller Datenschutz
 * Dieser Controller ruft nur das passende Template auf
 * @author: Maike Schroeder
 */
namespace controllers;

class Datenschutz extends Controller
{
    public function run(){
        $this->display("datenschutz");
    }
}// class