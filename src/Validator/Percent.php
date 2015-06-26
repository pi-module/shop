<?php
/**
 * Pi Engine (http://pialog.org)
 *
 * @link            http://code.pialog.org for the Pi Engine source repository
 * @copyright       Copyright (c) Pi Engine http://pialog.org
 * @license         http://pialog.org/license.txt New BSD License
 */

/**
 * @author Hossein Azizabadi <azizabadi@faragostaresh.com>
 */

namespace Module\Shop\Validator;

use Pi;
use Zend\Validator\AbstractValidator;

class Percent extends AbstractValidator
{
    const TAKEN = 'percentNotTrue';

    /**
     * @var array
     */
    protected $messageTemplates = array(
        self::TAKEN => 'Percent number should be between 1 to 99',
    );

    /**
     * Percent validate
     *
     * @param  mixed $value
     * @return boolean
     */
    public function isValid($value)
    {
        $this->setValue($value);
        if (null !== $value) {
            if ($value > 0 && $value < 100) {
                return true;
            } else {
                $this->error(static::TAKEN);
                return false;
            }
        }
        return true;
    }
}