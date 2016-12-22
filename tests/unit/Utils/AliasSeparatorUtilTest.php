<?php
namespace Test\Unit;

use App\Utils\AliasSeparatorUtil;
use CDM\Test\TestCase;

class AliasSeparatorUtilTest extends TestCase
{

    public function testShouldReturnEmptyJsonObjectWhenPassedWithEmptyString()
    {
        $string = '';
        $expected = json_encode([]);
        $actual = AliasSeparatorUtil::getAliases($string);
        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnEmptyArrayWhenNoAkaOrDbaIsFound()
    {
        $string = 'DOSHI, PRIYAKANT';
        $expected = json_encode([]);
        $actual = AliasSeparatorUtil::getAliases($string);
        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnAliasWhenAkaIsSuffixedWithWhitespace()
    {
        $string = 'BYERS, RAYMOND AKA FAYE BYERS';
        $expected = json_encode(['FAYE BYERS']);
        $actual = AliasSeparatorUtil::getAliases($string);
        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnAliasWhenDbaIsSuffixedWithWhitespace()
    {
        $string = 'BYERS, RAYMOND DBA FAYE BYERS';
        $expected = json_encode(['FAYE BYERS']);
        $actual = AliasSeparatorUtil::getAliases($string);
        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnAliasWhenAkaIsSuffixedWithColon()
    {
        $string = 'BRETTLER, NORMAN AKA:NORBERT';
        $expected = json_encode(['NORBERT']);
        $actual = AliasSeparatorUtil::getAliases($string);
        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnAliasWhenDbaIsSuffixedWithColon()
    {
        $string = 'BRETTLER, NORMAN DBA:NORBERT';
        $expected = json_encode(['NORBERT']);
        $actual = AliasSeparatorUtil::getAliases($string);
        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnAliasWhenAkaIsNotAllCaps()
    {
        $string = 'FURLONG aka COPOLA, KLEEY';
        $expected = json_encode(['COPOLA, KLEEY']);
        $actual = AliasSeparatorUtil::getAliases($string);
        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnAliasWhenDbaIsNotAllCaps()
    {
        $string = 'FURLONG dba COPOLA, KLEEY';
        $expected = json_encode(['COPOLA, KLEEY']);
        $actual = AliasSeparatorUtil::getAliases($string);
        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnAliasWhenAkaIsSuffixedWithColonAndWhitespace()
    {
        $string = 'KUKASH, MAJDI AKA: MIKE';
        $expected = json_encode(['MIKE']);
        $actual = AliasSeparatorUtil::getAliases($string);
        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnAliasWhenDbaIsSuffixedWithColonAndWhitespace()
    {
        $string = 'KUKASH, MAJDI DBA: MIKE';
        $expected = json_encode(['MIKE']);
        $actual = AliasSeparatorUtil::getAliases($string);
        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnAliasWhenAkaIsSuffixedWithWhitespaceColonAndWhitespace()
    {
        $string = 'OMAR, HATIEM AKA : HATIM';
        $expected = json_encode(['HATIM']);
        $actual = AliasSeparatorUtil::getAliases($string);
        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnAliasWhenDbaIsSuffixedWithWhitespaceColonAndWhitespace()
    {
        $string = 'OMAR, HATIEM DBA : HATIM';
        $expected = json_encode(['HATIM']);
        $actual = AliasSeparatorUtil::getAliases($string);
        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnAliasesWhenAkaIsMoreThanOne()
    {
        $string = 'BYERS, RAYMOND AKA FAYE BYERS AKA 1BYERS AKA FAYE';
        $expected = json_encode(['FAYE BYERS', '1BYERS', 'FAYE']);
        $actual = AliasSeparatorUtil::getAliases($string);
        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnAliasesWhenDbaIsMoreThanOne()
    {
        $string = 'BYERS, RAYMOND DBA FAYE BYERS DBA BYERS DBA FAYE';
        $expected = json_encode(['FAYE BYERS', 'BYERS', 'FAYE']);
        $actual = AliasSeparatorUtil::getAliases($string);
        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnAliasesWhenAkaAndDbaArePresent()
    {
        $string = 'BYERS, RAYMOND AKA FAYE BYERS DBA BYERS AKA FAYE';
        $expected = json_encode(['FAYE BYERS', 'BYERS', 'FAYE']);
        $actual = AliasSeparatorUtil::getAliases($string);
        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnValueWithoutAlias()
    {
        $string = 'BYERS, RAYMOND AKA FAYE BYERS DBA BYERS AKA FAYE';
        $expected = 'BYERS, RAYMOND';
        $actual = AliasSeparatorUtil::removeAliases($string);
        $this->assertEquals($expected, $actual);
    }
}
