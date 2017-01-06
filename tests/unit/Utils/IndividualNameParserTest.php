<?php namespace Test\Unit;

use App\Models\IndividualName;
use App\Utils\IndividualNameParser;
use CDM\Test\TestCase;

class IndividualNameParserTest extends TestCase
{

    public function testShouldReturnNullWhenGivenNameIsNull()
    {
        $name = '';
        $expected = new IndividualName();
        $actual = IndividualNameParser::parseName($name);
        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnParsedNameWhenGivenNameFormatIsLastNameCommaFirstName()
    {
        $name = 'ABASHKIN, ROMAN';

        $expected = new IndividualName();
        $expected->setFirstName('ROMAN');
        $expected->setLastName('ABASHKIN');

        $actual = IndividualNameParser::parseName($name);
        
        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnParsedNameWhenGivenNameFormatIsLastNameCommaFirstNameSpaceMiddleName()
    {
        $name = 'BORNHOLDT, HAE SUK';

        $expected = new IndividualName();
        $expected->setFirstName('HAE');
        $expected->setMiddleName('SUK');
        $expected->setLastName('BORNHOLDT');

        $actual = IndividualNameParser::parseName($name);

        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnParsedNameWhenGivenNameFormatIsLastNameCommaTwoNameFirstNameSpaceMiddleName()
    {
        $name = 'ABOOD, ABDOL MAJID S.';

        $expected = new IndividualName();
        $expected->setFirstName('ABDOL MAJID');
        $expected->setMiddleName('S.');
        $expected->setLastName('ABOOD');

        $actual = IndividualNameParser::parseName($name);

        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnParsedNameWhenGivenNameFormatIsLastNameSpaceSuffixCommaFirstName()
    {
        $name = 'PHIPPS JR., ERNEST';

        $expected = new IndividualName();
        $expected->setFirstName('ERNEST');
        $expected->setLastName('PHIPPS');
        $expected->setSuffix('JR.');

        $actual = IndividualNameParser::parseName($name);

        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnParsedNameWhenGivenNameFormatIsLastNameSpaceSuffixCommaFirstNameSpaceMiddleName()
    {
        $name = 'SCHOBERT III, WILLIAM B.';

        $expected = new IndividualName();
        $expected->setFirstName('WILLIAM');
        $expected->setMiddleName('B.');
        $expected->setLastName('SCHOBERT');
        $expected->setSuffix('III');

        $actual = IndividualNameParser::parseName($name);

        $this->assertEquals($expected, $actual);
    }

    public function testShouldReturnParsedNameWhenGivenNameFormatIsLastNameSpaceSuffixCommaTwoNameFirstNameSpaceMiddleName()
    {
        $name = 'SCHOBERT III, JOHN WILLIAM B.';

        $expected = new IndividualName();
        $expected->setFirstName('JOHN WILLIAM');
        $expected->setMiddleName('B.');
        $expected->setLastName('SCHOBERT');
        $expected->setSuffix('III');

        $actual = IndividualNameParser::parseName($name);

        $this->assertEquals($expected, $actual);
    }

    public function test()
    {
        $name = 'BRETTLER, NORMAN AKA NORBERT BRETTLER';

        $expected = new IndividualName();
        $expected->setFirstName('NORMAN AKA NORBERT');
        $expected->setMiddleName('BRETTLER');
        $expected->setLastName('BRETTLER');

        $actual = IndividualNameParser::parseName($name);

        $this->assertEquals($expected, $actual);
    }

}
