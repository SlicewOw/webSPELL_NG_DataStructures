<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

use webspell_ng\Utils\ValidationUtils;

final class ValidationUtilsTest extends TestCase
{

    /**
     * Integer validations
     */

    public function testIfIntegerValueIsPassed(): void
    {
        $this->assertTrue(ValidationUtils::validateInteger(1));
    }

    public function testIfInvalidNumberIsDetected(): void
    {
        $this->assertFalse(ValidationUtils::validateInteger(-1, true));
    }

    public function testIfValidNumberIsDetected(): void
    {
        $this->assertTrue(ValidationUtils::validateInteger(-1, false));
    }

    /**
     * Array validations
     */

    public function testIfValidEmptyArrayIsDetected(): void
    {
        $this->assertTrue(ValidationUtils::validateArray(array(), false));
    }

    public function testIfValidArrayIsDetected(): void
    {
        $this->assertTrue(ValidationUtils::validateArray(array("123"), true));
    }

    public function testIfInvalidEmptyArrayIsDetected(): void
    {
        $this->assertFalse(ValidationUtils::validateArray(array(), true));
    }

    /**
     * URL validations
     */

    public function testIfValidUrlIsDetected(): void
    {
        $this->assertTrue(ValidationUtils::validateUrl("http://gaming.myrisk-ev.de"));
        $this->assertTrue(ValidationUtils::validateUrl("https://gaming.myrisk-ev.de"));
    }

    public function testIfInvalidUrlIsDetected(): void
    {
        $this->assertFalse(ValidationUtils::validateUrl("gaming.myrisk-ev.de"));
        $this->assertFalse(ValidationUtils::validateUrl("random_string"));
        $this->assertFalse(ValidationUtils::validateUrl("string with whitespace"));
    }

    /**
     * Email validations
     */

    public function testIfValidEmailIsDetected(): void
    {
        $this->assertTrue(ValidationUtils::validateEmail("test@test.de"));
    }

    public function testIfInvalidEmailIsDetected(): void
    {
        $this->assertFalse(ValidationUtils::validateEmail("test@test"));
    }

    public function testIfSpamEmailIsDetectedByEmptyString(): void
    {
        $this->assertFalse(ValidationUtils::validateEmail(""));
    }

    public function testIfSpamEmailIsDetectedByKeywordSpam(): void
    {
        $this->assertFalse(ValidationUtils::validateEmail("test@spam.com"));
    }

    public function testIfSpamEmailIsDetectedByTopLevelDomain(): void
    {
        $this->assertFalse(ValidationUtils::validateEmail("test@mail.club"));
    }

}
