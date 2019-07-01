<?php
/**
 * Created by PhpStorm.
 * User: yusuf
 * Date: 28.06.2019
 * Time: 10:36
 */

namespace Rest\Validation;


use Zend\Validator\AbstractValidator;
use Zend\Validator\NotEmpty;
use Zend\Validator\StringLength;
use Zend\Validator\ValidatorChain;

/**
 * Class ValidatePassword
 * @package Rest\Validation
 */
class ValidatePassword extends AbstractValidator
{



    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param $email
     * @return bool
     */
    public function isValid($password)
    {
        $this->setValue($password);
        $isValid = true;

        $validator = new ValidatorChain();
        $validator->attach(new NotEmpty());
        $validator->attach(new StringLength(array('min' => 6)));

        if (!$validator->isValid($password)) {

            foreach ($validator->getMessages() as $key => $message) {

                $this->abstractOptions['messageTemplates'][$key] = $message;
                $this->error($key, $message);
            }


            $isValid = false;
        }

        return $isValid;
    }
}