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
use Laminas\Validator\AbstractValidator;

class CodeDuplicate extends AbstractValidator
{
    const TAKEN = 'codeExists';

    /**
     * @var array
     */
    protected $messageTemplates
        = [
            self::TAKEN => 'This code already exists',
        ];

    protected $options
        = [
            'module', 'table',
        ];

    /**
     * code validate
     *
     * @param mixed $value
     * @param array $context
     *
     * @return boolean
     */
    public function isValid($value, $context = null)
    {
        $this->setValue($value);
        if (null !== $value) {
            $where = ['code' => $value];
            if (!empty($context['id'])) {
                $where['id <> ?'] = $context['id'];
            }
            $rowSet = Pi::model($this->options['table'], $this->options['module'])->select($where);
            if ($rowSet->count()) {
                $this->error(static::TAKEN);
                return false;
            }
        }
        return true;
    }
}
