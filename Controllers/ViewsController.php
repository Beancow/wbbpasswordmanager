<?php


namespace WBB;


class ViewsController
{
    public function RenderView($FileName){
        require __DIR__."/../Views/" .$FileName.".php";
    }
    public function RenderPartialView($FileName){
        require __DIR__."/../Views/Partials/" .$FileName.".php";
    }
}