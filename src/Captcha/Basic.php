<?php namespace WebDevEtc\BlogEtc\Captcha;

use DomainException;

/**
 * Class Basic
 * @package WebDevEtc\BlogEtc\Captcha
 */
class Basic extends CaptchaAbstract
{
    public function __construct()
    {
        if (!config('blogetc.captcha.basic_question') || !config('blogetc.captcha.basic_answers')) {
            throw new DomainException('Invalid question or answers for captcha');
        }
    }

    /**
     * What should the field name be (in the <input type='text' name='????'>)
     *
     * @return string
     */
    public function captcha_field_name(): string
    {
        return 'captcha';
    }

    /**
     * What view file should we use for the captcha field?
     *
     * @return string
     */
    public function view(): string
    {
        return 'blogetc::captcha.basic';
    }

    /**
     * What rules should we use for the validation for this field?
     *
     * Enter the rules here, along with captcha validation.
     *
     * @return array
     */
    public function rules(): array
    {
        $validAnswerCheck = static function ($attribute, $value, $fail) {
            // get correct answers
            $answers = config('blogetc.captcha.basic_answers');

            // lower case submitted value and the answers
            $value = strtolower(trim($value));
            $answers = strtolower($answers);

            $answers_array = array_map('trim', explode(',', $answers));

            if (!in_array($value, $answers_array, true)) {
                return $fail('The captcha field is incorrect.');
            }
        };

        return [
            'required',
            'string',
            $validAnswerCheck,
        ];
    }
}
