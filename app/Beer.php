<?php

namespace App;

use Exception;
use Pintlabs_Service_Brewerydb;
use SimpleXMLElement;

class Beer implements Export
{
    const DEFAULT_LIMIT = 10;

    private $items = [];
    private $format = 'json';
    private $limit = 10;

    public function __construct($limit, $format)
    {
        $this->format = $format;
        $this->limit = $limit;
    }

    /**
     * Get beets with limit
     * @todo pagination - now limit is 50 items per page.
     * @return Beer
     */
    public function getItems()
    {
        $bdb = new Pintlabs_Service_Brewerydb(config('apiKey'));
        $bdb->setFormat($this->format);

        $results = [];
        try {
            App::cliLog('Getting beers:', false);

            $params = [
                'limit' => $this->limit,
                'abv'  => '0,100'
            ];

            $results = $bdb->request('beers', $params, 'GET');
        } catch (Exception $e) {
            App::cliLog($e->getMessage());
        }

        if (isset($results['status']) && $results['status'] == 'failure') {
            App::cliLog($results['errorMessage']);
        }

        $this->parse($results);

        return $this;
    }

    public function save()
    {
        App::cliLog("Saving to $this->format file", false);

        try {
            call_user_func([$this, 'export' . ucfirst($this->format)]);
        } catch (Exception $e) {
            App::cliLog($e->getMessage());
        }
    }

    /**
     * Parse data from api provider
     *
     * @param $data
     * @return void
     */
    private function parse($data): void
    {
        if (!isset($data['data'])) {
            App::cliLog('No records found');
        }

        $this->items = $data['data'];

        $parsedData = [];
        foreach ($this->items as $item) {
            $parsedData[] = [
                'name' => $item['name'],
                'description' => isset($item['description']) ? $item['description'] : 'No Description',
                'image' => isset($item['labels']) ? $item['labels']['large'] : "No Image"
            ];
        }

        $this->items = $parsedData;
    }

    /**
     * Export Data to Json format
     *
     *
     * @return string
     */
    public function exportJson()
    {
        $time = time();
        $filePath = ROOT_DIR . "/data/beer_$time.json";
        try {
            file_put_contents($filePath, json_encode($this->items));

            App::cliLog("Successfully exported to $filePath");
        } catch (Exception $e) {
            App::cliLog($e->getMessage());
        }
    }

    /**
     * Export data to HTML format
     *
     * @return string
     */
    public function exportHtml()
    {
        $html = '<table>
                <tr>
                    <td>Name</td>
                    <td>Description</td>
                    <td>Image url</td>
                </tr>';

        foreach ($this->items as $item) {
            $html .= "<tr>
                    <td>{$item['name']}</td>
                    <td>{$item['description']}</td>
                    <td>{$item['image']}</td>
                </tr>";
        }

        $html .= '</table>';

        $time = time();
        $filePath = ROOT_DIR . "/data/beer_$time.html";

        try {
            file_put_contents($filePath, $html);

            App::cliLog("Successfully exported to $filePath");
        } catch (Exception $e) {
            App::cliLog($e->getMessage());
        }
    }

    /**
     * Export data to XML format
     *
     * @return string
     */
    public function exportXml()
    {
        $time = time();
        $filePath = ROOT_DIR . "/data/beer_$time.xml";

        $xml = new SimpleXMLElement("<?xml version=\"1.0\"?><beers></beers>");
        array_to_xml($this->items,$xml);

        try {
            $xml->asXML($filePath);

            App::cliLog("Successfully exported to $filePath");
        } catch (Exception $e) {
            App::cliLog($e->getMessage());
        }
    }
}