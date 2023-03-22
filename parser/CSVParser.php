<?php

namespace parser;

use models\Validators;

use Exception;

class CSVParser extends Validators
{
    private string $filename;
    private array $newRows = [];
    private array $errors = [];

    public function __construct($filename)
    {
        $this->filename = $filename;
    }

    /**
     * @throws \Exception
     */
    public function readCSVFile()
    {
        if(file_exists($this->filename)) {
            $file = fopen($this->filename, 'r');
            $i = 0;
            $columnsKey = [];
            while (($line = fgetcsv($file)) !== FALSE) {
                if($i > 0) {
                    if(!isset($newRows[$line['external_id']])) {
                        $hasErrors = false;
                        foreach ($line as $key => $value) {
                            $return = $this->validationHandler($columnsKey[$key], $value);
                            if ($return['success'] == false) {
                                $hasErrors = true;
                                $this->errors[$i][$columnsKey[$key]] = $return['errors'];
                            }
                        }

                        if(!$hasErrors) {
                            $this->newRows[] = $line;
                        }
                    }
                } else {
                    $columnsKey = $line;
                }
                $i++;
            }
            fclose($file);

            return true;
        }

        throw new Exception('File not found');
    }

    public function createCSVFile()
    {
        var_dump($this->newRows);
    }

    public function createErrorsJson()
    {
        var_dump($this->errors);
    }
}