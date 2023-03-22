<?php

namespace models;

use Exception;

abstract class Validators
{

    protected function validationHandler(string $attribute, ?string $value) :array
    {
        switch ($attribute) {
            case 'name':
                return $this->validateName($value);
            case 'age':
                return $this->validateAge($value);
            case 'dni':
                return $this->validateDocumentNumber($value);
            case 'source':
                return $this->validateSource($value);
            case 'tags':
                return $this->validateTags($value);
            case 'phone':
                return $this->validatePhone($value);
            case 'external_id':
                return $this->validateID($value);
        }

        throw new Exception('Invalid Column Validator');
    }

    /**
     * ID externo: un integer entre el 10000 y el 99999
     */
    private function validateID(string $id) :array
    {
        $success = true;
        $errors = [];

        if(!is_numeric($id)) {
            $success = false;
            $errors[] = 'No es un numero entero';
        }

        if(!($id >= 10000 && $id <= 99999)) {
            $success = false;
            $errors[] = 'El ID debe ser mayor a 10000 y menor a 99999';
        }

        return ['success' => $success, 'errors' => $errors];
    }


    /**
     * Nombre: deberá estar compuesto de primer nombre y apellido separado por espacios en la forma “Primernombre Apellido”
     * con la posibilidad opcional de que contenga la inicial del segundo nombre separada con un punto en la forma “Primernombre S. Apellido”.
     */
    private function validateName(string $name) :array
    {
        $success = true;
        $errors = [];

        $nameArray = explode(' ', trim($name));
        if(count($nameArray) < 2) {
            $success = false;
            $errors[] = 'El nombre no tiene un formato valido.';
        }

        if(empty($nameArray[0]) || strlen($nameArray[0]) == 2 && substr($nameArray[0], -1) === '.') {
            $success = false;
            $errors[] = 'El nombre no tiene un formato valido.';
        }

        if(count($nameArray) === 3) {
            if(strlen($nameArray[1]) > 2 || substr($nameArray[1], -1) !== '.') {
                $success = false;
                $errors[] = 'El segundo nombre no tiene un formato valido.';
            }
        }

        return ['success' => $success, 'errors' => $errors];
    }

    /**
     * DNI: un integer de 11 dígitos
     */
    private function validateDocumentNumber(string $dni) :array
    {
        $success = true;
        $errors = [];

        if(!is_numeric($dni)) {
            $success = false;
            $errors[] = 'No es un numero entero.';
        }

        if(strlen((string)$dni) != 11) {
            $success = false;
            $errors[] = 'El DNI tiene un numero de caracteres incorrecto.';
        }

        return ['success' => $success, 'errors' => $errors];
    }

    /**
     * Edad: Un integer del 1 al 125
     */
    private function validateAge(string $dni) :array
    {
        $success = true;
        $errors = [];

        if(!is_numeric($dni)) {
            $success = false;
            $errors[] = 'No es un numero entero';
        }

        if(!($dni >= 1 && $dni <= 125)) {
            $success = false;
            $errors[] = 'La edad debe ser de 1 a 125 años';
        }

        return ['success' => $success, 'errors' => $errors];
    }

    /**
     * Fuente: un string con cualquiera de los valores válidos (“Google forms”, “Facebook Leads”, “Email response”, “Manual registration”), case insensitive.
     */
    private function validateSource(string $source) :array
    {
        $validSources = ["Google forms", "Facebook Leads", "Email response", "Manual registration"];

        $success = false;
        $errors = ['No es un origen valido'];

        foreach ($validSources as $validSource) {
            if (strtolower($validSource) == strtolower($source)) {
                $success = true;
                $errors = [];
            }
        }

        return ['success' => $success, 'errors' => $errors];
    }

    /**
     * Tags: un conjunto de strings separados por “|” y que solo pueden contener caracteres alfanuméricos y barras bajas ( _ )
     */
    private function validateTags(string $tags) :array
    {
        $success = true;
        $errors = [];

        $tagsArray = explode('|', $tags);

        foreach ($tagsArray as $item) {
            if(!preg_match("/^[a-zA-Z0-9_]*$/", $item)) {
                $success = false;
                $errors[] = 'No es un tag válido';
            }
        }

        return ['success' => $success, 'errors' => $errors];
    }

    /**
     * Teléfono: Un string con 11 dígitos que pueden o no estar separados por espacios y guiones,
     * y que opcionalmente pueden estar prefijados con el numero de area de argentina (+54), por ejemplo “+54 9 11 1234-5678”
     */
    private function validatePhone(string $phone) :array
    {
        $success = true;
        $errors = [];

        $phoneNumber = str_replace(' ', '', $phone);
        $phoneNumber = str_replace('-', '', $phoneNumber);

        if(strlen($phoneNumber) != 11 && strlen($phoneNumber) != 14) {
            $success = false;
            $errors[] = 'No es un numero válido';
        }

        if(strlen($phoneNumber) == 14 && substr($phoneNumber, 0, 3) != '+54') {
            $success = false;
            $errors[] = 'No es un numero válido';
        }

        return ['success' => $success, 'errors' => $errors];
    }

}