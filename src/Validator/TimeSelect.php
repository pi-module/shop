<?php
/**
 * Pi Engine (http://piengine.org)
 *
 * @link            http://code.piengine.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://piengine.org
 * @license         http://piengine.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Shop\Validator;

use DateTime;
use Pi;
use Zend\Validator\AbstractValidator;

class TimeSelect extends AbstractValidator
{
    const TAKEN = 'timeSelect';

    /**
     * @var array
     */
    protected $messageTemplates
        = [
            self::TAKEN => 'Time format is not valid, true format is : Y-m-d H:i:s , for example : 2015-11-12 08:17:43',
        ];

    protected $options = [];

    /**
     * Name validate
     *
     * @param  mixed $value
     * @param  array $context
     *
     * @return boolean
     */
    public function isValid($value, $context = null)
    {
        $this->setValue($value);
        if (null !== $value) {
            $format = 'Y-m-d H:i:s';
            $date   = DateTime::createFromFormat($format, $value);
            if (!$date || $date->format($format) != $value) {
                $this->error(static::TAKEN);
                return false;
            }
        }
        return true;
    }
}