<?php

namespace Prettus\FIQLParser;

/**
 * @author Anderson Andrade <contact@andersonandra.de>
 */
class Constants
{
    public const PCT_ENCODING_REGEX = '%[A-Fa-f0-9]{2}';
    public const UNRESERVED_REGEX = '[A-Za-z0-9-\\._~áàâãéèêíïóôõöúçñÁÀÂÃÉÈÍÏÓÔÕÖÚÇÑ ]';
    public const FIQL_DELIM_REGEX = "[!$'*+]";
    public const COMPARISON_REGEX = '(=[A-Za-z]*|' . self::FIQL_DELIM_REGEX . ')=';
    public const SELECTOR_REGEX = '(' . self::UNRESERVED_REGEX . '|' . self::PCT_ENCODING_REGEX . ')+';
    public const GROUP_IN_REGEX = '\\((' . self::UNRESERVED_REGEX . '+)\\)';
    public const ARG_CHAR_REGEX = '(' . self::GROUP_IN_REGEX . '|' . self::UNRESERVED_REGEX . '|' . self::PCT_ENCODING_REGEX . '|' . self::FIQL_DELIM_REGEX . '|=|:)';
    public const ARGUMENT_REGEX = '' . self::ARG_CHAR_REGEX . '+';

    public const CONSTRAINT_REGEX = '(' . self::SELECTOR_REGEX . ')((' . self::COMPARISON_REGEX . ')(' . self::ARGUMENT_REGEX . '))?';

    public const CONSTRAINT_COMP = '/' . self::CONSTRAINT_REGEX . '(.*)/';
    public const COMPARISON_COMP = '/^' . self::COMPARISON_REGEX . '$/';
}
