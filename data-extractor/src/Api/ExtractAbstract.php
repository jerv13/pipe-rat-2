<?php

namespace Reliv\PipeRat2\DataExtractor\Api;

/**
 * @author James Jervis - https://github.com/jerv13
 */
abstract class ExtractAbstract
{
    /**
     * getPropertyList
     *
     * @param array $options
     * @param array $default
     *
     * @return array
     */
    public function getPropertyList(array $options, $default = [])
    {
        if (array_key_exists(OptionsExtract::PROPERTY_LIST, $options)) {
            return $options[OptionsExtract::PROPERTY_LIST];
        }

        return $default;
    }

    /**
     * @param array $options
     * @param int   $default
     *
     * @return int
     */
    public function getPropertyDepthLimit(array $options, $default = 1)
    {
        if (array_key_exists(OptionsExtract::PROPERTY_DEPTH_LIMIT, $options)) {
            return (int)$options[OptionsExtract::PROPERTY_DEPTH_LIMIT];
        }

        return (int) $default;
    }
}
