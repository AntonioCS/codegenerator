<?php
declare(strict_types=1);

namespace Inflyter\CodeGenerator;


use Inflyter\CodeGenerator\Type\CGFile;

class CodeGenerator
{
    public const DEFAULT_HANDLE_OBJECT = 'DEFAULT_HANDLE_OBJECT';

    public static function init(?string $name = null): CGFile
    {
        return new CGFile($name);
    }

    public static function nullString() : string
    {
        return 'null';
    }

    public static function convertDateTimeToString(?\DateTimeInterface $dt, string $format = 'Y-m-d H:i:s.u') : string
    {
        return ($dt) ? "new \\DateTime('{$dt->format($format)}')" : 'null';
    }

    public static function convertBoolToString(bool $value) : string
    {
        return ($value) ? 'true' : 'false';
    }

    public static function convertToStringValue(string $value) : string
    {
        $normalized = str_replace(
            ['\"', '"', "\n", "\r", "\t"], //I need to replace \" with " so that it doesn't become \\"
            ['"', '\"', "\\n", "\\r", "\\t"],
            $value
        );
        return '"' . $normalized . '"';
    }

    public static function convertArrayToJsonValue(array $value) : string
    {
        $json = json_encode($value, JSON_THROW_ON_ERROR);
        return "json_decode('$json', true)";
    }

    public static function convertToSerializeType(object $obj) : string
    {
        $serializedData = str_replace("'", "\'", serialize($obj));
        return "unserialize('$serializedData')";
    }

    /**
     * @param array<string, string> $data
     * @return string
     */
    public static function convertArrayToTable(array $data) : string
    {


    }

    public static function getValueOrNull(mixed $value = null)
    {
        if ($value === null)
            return 'null';

        return $value;
    }

    public static function convertStringArray(array $data) : string
    {
        return self::convertArrayToStringFormat($data, '","', "\"");
    }

    public static function convertNumArray(array $data) : string
    {
        return self::convertArrayToStringFormat($data);
    }

    public static function convertArrayToStringFormat(array $data, string $separator = ',', string $wrapper = '') : string
    {
        $result = '[]';
        if (!empty($data)) {
            $result = "[$wrapper" . implode($separator, $data) . "$wrapper]";
        }

        return $result;
    }

    public static function convertFromArrayToStringFormatArray(array $data, array $callablesForObjectTypes = [], bool $ignoreNumericKeys = true) : string
    {
        $result = [];

        foreach ($data as $k => $item) {
            $code = '';

            if (is_string($k)) {
                $code .= self::getValueInCorrectForm($k) . ' => ';
            }
            $code .= self::getValueInCorrectForm($item, $callablesForObjectTypes);

            $result[] = $code;
        }

        return self::convertArrayToStringFormat($result);
    }

    public static function getValueInCorrectForm(mixed $data, array $callablesForObjectTypes = [], bool $ignoreNumericKeysInArrays = true): mixed
    {
        $result = null;

        if ($data === null) {
            $result = self::nullString();
        } elseif (is_object($data)) {
            if ($data instanceof \DateTimeInterface) {
                $result = self::convertDateTimeToString($data);
            } elseif (method_exists($data, '__toString')) {
                $result = $data->__toString();
            } else {
                $classType = get_class($data);
                if (isset($callablesForObjectTypes[$classType]) && is_callable($callablesForObjectTypes[$classType])) {
                    $result = $callablesForObjectTypes[$classType]($data);
                } elseif (isset($callablesForObjectTypes[self::DEFAULT_HANDLE_OBJECT]) && is_callable($callablesForObjectTypes[self::DEFAULT_HANDLE_OBJECT])) {
                    $result = $callablesForObjectTypes[self::DEFAULT_HANDLE_OBJECT]($data);
                } else {
                    $result = self::convertToSerializeType($data);
                }
            }
        } elseif (is_array($data)) {
            $result = (empty($data) ? '[]' : self::convertFromArrayToStringFormatArray($data, $callablesForObjectTypes, $ignoreNumericKeysInArrays));
        } elseif (is_string($data)) { //NOTE: do not use empty() as a string with value '0' gives true
            $result = $data === '' ? "''" : self::convertToStringValue($data);
        } elseif (is_bool($data)) {
            $result = self::convertBoolToString($data);
        } else {
            $result = $data;
        }

        return $result;
    }
}