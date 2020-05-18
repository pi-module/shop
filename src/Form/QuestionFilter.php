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

namespace Module\Shop\Form;

use Module\System\Validator\UserEmail as UserEmailValidator;
use Pi;
use Laminas\InputFilter\InputFilter;

class QuestionFilter extends InputFilter
{
    public function __construct()
    {
        // product
        $this->add(
            [
                'name'     => 'product',
                'required' => true,
            ]
        );
        // name
        $this->add(
            [
                'name'     => 'name',
                'required' => true,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );
        // email
        $this->add(
            [
                'name'       => 'email',
                'required'   => true,
                'filters'    => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
                'validators' => [
                    [
                        'name'    => 'EmailAddress',
                        'options' => [
                            'useMxCheck'     => false,
                            'useDeepMxCheck' => false,
                            'useDomainCheck' => false,
                        ],
                    ],
                    new UserEmailValidator(
                        [
                            'blacklist'         => false,
                            'check_duplication' => false,
                        ]
                    ),
                ],
            ]
        );
        // text_ask
        $this->add(
            [
                'name'     => 'text_ask',
                'required' => true,
                'filters'  => [
                    [
                        'name' => 'StringTrim',
                    ],
                ],
            ]
        );
    }
}