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

use Pi;
use Zend\Validator\AbstractValidator;

class Percent extends AbstractValidator
{
    const TAKEN = 'percentNotTrue';

    /**
     * @var array
     */
    protected $messageTemplates
        = [
            self::TAKEN => 'Percent number should be between 1 to 99',
        ];

    /**
     * Percent validate
     *
     * @param mixed $value
     *
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