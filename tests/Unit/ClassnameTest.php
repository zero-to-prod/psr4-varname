<?php

namespace Tests\Unit;

use Tests\TestCase;
use Zerotoprod\Psr4VarName\VarName;

class ClassnameTest extends TestCase
{
    /**
     * @test
     * @dataProvider varNameProvider
     */
    public function generate($input, $expected): void
    {
        $this->assertEquals($expected, VarName::generate($input));
    }

    public static function varNameProvider(): array
    {
        return [
            ["my awesome var", "my_awesome_var"],
            ["123 invalid var", "_123_invalid_var"],
            ["some/random\\var", "some_random_var"],
            ["weird%characters*in^name", "weird_characters_in_name"],
            ["my_var_name", "my_var_name"],
            ["!@#$%^&*()", "_"],
            ["", "_"],
            ["  multiple   spaces  ", "multiple_spaces"],
            ["_private_var", "_private_var"],
            ["name\\\\var", "name_var"],
            ["var123name", "var123name"],
            ["Var123name", "Var123name"],
            ["mix_of-different/separators\\here", "mix_of_different_separators_here"],
            ["fooBarBaz", "fooBarBaz"],
            ["FooBarBaz", "FooBarBaz"],
            ["userID", "userID"],
            ["123numberStart", "_123numberStart"],
            ["special—char", "special_char"],
            ["emoji😊test", "emoji_test"],
            ["汉字漢字", "_"],
        ];
    }
}