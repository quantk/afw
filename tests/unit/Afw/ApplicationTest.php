<?php namespace Afw;


class ApplicationTest extends \Codeception\Test\Unit
{
//region SECTION: Fields
    /**
     * @var \UnitTester
     */
    protected $tester;
//endregion Fields

//region SECTION: Protected
    protected function _before()
    {
    }

    protected function _after()
    {
    }
//endregion Protected

//region SECTION: Public
    /**
     * @throws \Exception
     */
    public function testSomeFeature()
    {
        $application = new Application();
        $this->assertInstanceOf(Application::class, $application);
    }
//endregion Public
}