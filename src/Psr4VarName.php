<?php

namespace Zerotoprod\Psr4VarName;

/**
 * Generates a valid PSR-4 Compliant variable name from a string.
 *
 * ```
 * use Zerotoprod\Psr4Classname\VarName;
 *
 * VarName::generate('weird%characters*in^name'); // 'weird_characters_in_name';
 * ```
 */
class Psr4VarName
{
    /**
     * Generates a valid PSR-4 Compliant variable name from a string.
     */
    public static function generate(string $input, ?string $separator = '_'): string
    {
        $output = preg_replace(
            [
                '/\s+/',        // Replace 1+ whitespace chars with '_'
                '/[\/\\\\]+/',  // Replace 1+ slashes/backslashes with '_'
                '/\W/',         // Replace any non-alphanumeric/underscore char with '_'
                '/_+/'          // Collapse consecutive underscores down to a single '_'
            ],
            [$separator, $separator, $separator, $separator],
            trim($input)
        );

        if ($output === '') {
            return $separator;
        }

        return preg_match('/^\d/', $output)
            ? $separator . $output
            : $output;
    }
}