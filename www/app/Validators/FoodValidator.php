<?php

namespace App\Validators;

use \Prettus\Validator\Contracts\ValidatorInterface;
use \Prettus\Validator\LaravelValidator;

/**
 * Class FoodValidator.
 *
 * @package namespace App\Validators;
 */
class FoodValidator extends LaravelValidator
{
    /**
     * Validation Rules
     *
     * @var array
     */
    protected $rules = [
        ValidatorInterface::RULE_CREATE => ['food' => 'required'],
        ValidatorInterface::RULE_UPDATE => [],
    ];
}