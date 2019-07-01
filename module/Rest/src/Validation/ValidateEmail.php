<?php
/**
 * Created by PhpStorm.
 * User: yusuf
 * Date: 28.06.2019
 * Time: 10:36
 */

namespace Rest\Validation;


use Zend\Validator\AbstractValidator;
use Zend\Validator\EmailAddress;
use Zend\Validator\Exception;
use Zend\Validator\NotEmpty;
use Zend\Validator\ValidatorChain;

/**
 * Class ValidateEmail
 * @package Rest\Validation
 */
class ValidateEmail extends AbstractValidator
{



    /**
     * Returns true if and only if $value meets the validation requirements
     *
     * If $value fails validation, then this method returns false, and
     * getMessages() will return an array of messages that explain why the
     * validation failed.
     *
     * @param  mixed $value
     * @return bool
     * @throws Exception\RuntimeException If validation of $value is impossible
     */
    public function isValid($email)
    {
        $this->setValue($email);

        $isValid = true;


        $validator = new ValidatorChain();
        $validator->attach(new EmailAddress());
        $validator->attach(new NotEmpty());

        if (!$validator->isValid($email)) {

            foreach ($validator->getMessages() as $key => $message) {

                $this->abstractOptions['messageTemplates'][$key] = $message;
                $this->error($key, $message);
            }


            $isValid = false;
        }

        return $isValid;
    }
}