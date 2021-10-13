<?php

namespace Prettus\FIQL;

class Constants
{
    const PCT_ENCODING_REGEX = '%[A-Fa-f0-9]{2}';
    const UNRESERVED_REGEX = '[A-Za-z0-9-\\._~áàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]';
    const FIQL_DELIM_REGEX = "[!$'*+]";
    const COMPARISON_REGEX = '(=[A-Za-z]*|' . self::FIQL_DELIM_REGEX . ')=';
    const SELECTOR_REGEX = '(' . self::UNRESERVED_REGEX . '|' . self::PCT_ENCODING_REGEX . ')+';
    const GROUP_IN_REGEX = '\\((' . self::UNRESERVED_REGEX . '+)\\)';
    const ARG_CHAR_REGEX = '(' . self::GROUP_IN_REGEX . '|' . self::UNRESERVED_REGEX . '|' . self::PCT_ENCODING_REGEX . '|' . self::FIQL_DELIM_REGEX . '|=|:)';
    const ARGUMENT_REGEX = '' . self::ARG_CHAR_REGEX . '+';

    const CONSTRAINT_REGEX = '(' . self::SELECTOR_REGEX . ')((' . self::COMPARISON_REGEX . ')(' . self::ARGUMENT_REGEX . '))?';

    const CONSTRAINT_COMP = '/' . self::CONSTRAINT_REGEX . '(.*)/';
    const COMPARISON_COMP = '/^' . self::COMPARISON_REGEX . '$/';
}
