<?php

namespace parser;

use models\Validators;
use Exception;

class CSVParser extends Validators
{
    private array $columns = [];
    private array $newRows = [];
    private array $errors = [];

    /**
     * @throws \Exception
     */
    public function readCSVFile(string $filename) :void
    {
        if(!file_exists($filename)) {
            throw new Exception('File not found');
        }

        $file = fopen($filename, 'r');
        $addedExternalIds = [];
        $i = 0;
        while (($line = fgetcsv($file)) !== FALSE) {
            if($i == 0) {
                $this->columns = $line;
            } else { //if($i > 75 && $i < 80) for debug part of the CSV
                if(!isset($addedExternalIds[$line[6]])) {
                    $hasErrors = false;
                    foreach ($line as $key => $value) {
                        $return = $this->validationHandler($this->columns[$key], $value);
                        if ($return['success'] == false) {
                            $hasErrors = true;
                            $this->errors[$i][$this->columns[$key]] = $return['errors'];
                        }
                    }

                    if(!$hasErrors) {
                        $addedExternalIds[$line[6]] = 0;
                        $this->addOrUpdateNewRow($line);
                    }
                } else {
                    $this->errors[$i]['external_id'][] = 'El ID externo ya existe.';
                }
            }
            $i++;
        }
        fclose($file);

        echo 'Archivo procesado correctamente'.PHP_EOL;
    }

    private function addOrUpdateNewRow(array $line) :void
    {
        $pk = $line[1].'-'.$line[2].'-'.preg_replace ("/[^\d]/", "", $line[5]);
        if(!isset($this->newRows[$pk])) {
            $this->newRows[$pk] = $line;
        } else {
            $currentTags = explode('|', $this->newRows[$pk][4]);
            foreach (explode('|', $line[4]) as $tag) {
                if(!in_array($tag, $currentTags)) {
                    $currentTags[] = $tag;
                }
            }

            $this->newRows[$pk][4] = implode('|', $currentTags);
            $this->newRows[$pk][6] = $line[6];
        }
    }

    public function createCSVFile() :void
    {
        if(!empty($this->newRows)) {
            // Open a file in write mode ('w')
            $fp = fopen('output/'.date('Y-m-d_H:i').'_result.csv', 'w');

            fputcsv($fp, $this->columns);

            foreach ($this->newRows as $fields) {
                fputcsv($fp, $fields);
            }

            fclose($fp);

            echo 'Nuevo archivo con casos exitosos fue creado correctamente'.PHP_EOL;
        }
    }


    public function createErrorsJson() :void
    {
        if(!empty($this->errors)) {
            file_put_contents('output/'.date('Y-m-d_H:i').'_errors.json', json_encode($this->errors));

            echo 'Archivo con errores fue creado correctamente'.PHP_EOL;
        }
    }
}