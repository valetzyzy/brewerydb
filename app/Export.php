<?php

namespace App;


interface Export
{

    public function exportJson();
    public function exportHtml();
    public function exportXml();

}