<?php

namespace App\Enums;

trait EnumUtilities {
    /**
     * This will list all of the values to a particular given Enum
     *      and return them as a comma separated array of values.
     *
     * @access public static
     * @return string
     */
    public static function listValues(): string
    {
        $values = '';

        foreach (self::cases() as $item) {
            if ($values !== '') {
                $values .= ",";
            }

            $values .= $item->value;
        }

        return $values;
    }

    /**
     * This will extract all values from a given enum and assemble it
     *      inside an array.
     *
     * @access public static
     * @return array
     */
    public static function getValues(): array
    {
        $items = [];

        foreach (self::cases() as $item) {
            $items[] = $item->value;
        }

        return $items;
    }
}
