<?php
namespace Prettus\FIQL;

class Constants {
    const PCT_ENCODING_REGEX = '%[A-Fa-f0-9]{2}';
    const UNRESERVED_REGEX = '[A-Za-z0-9-\._~]';
    const FIQL_DELIM_REGEX = "[!$'*+]";
    const COMPARISON_REGEX = '(=[A-Za-z]*|'.self::FIQL_DELIM_REGEX.')=';
    const SELECTOR_REGEX = '(' . self::UNRESERVED_REGEX . '|' . self::PCT_ENCODING_REGEX . ')+';
    const ARG_CHAR_REGEX = '(' . self::UNRESERVED_REGEX . '|' . self::PCT_ENCODING_REGEX . '|' . self::FIQL_DELIM_REGEX . '|' . '=|:)';
    const ARGUMENT_REGEX = self::ARG_CHAR_REGEX . '+';
    const CONSTRAINT_REGEX = '(' . self::SELECTOR_REGEX . ')((' . self::COMPARISON_REGEX . ')' . '(' . self::ARGUMENT_REGEX . '))?(.*)';
}