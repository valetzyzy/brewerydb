<?php

namespace App;


interface Export
{

    /**
     * Export to json format
     * @return mixed
     */
    public function exportJson();

    /**
     * Export to HTML format
     * @return mixed
     */
    public function exportHtml();

    /**
     * Export to Xml format
     * @return mixed
     */
    public function exportXml();

}