<?php
namespace Slub\MatomoReporter\Tests\Unit\Domain\Model;

/**
 * Test case.
 *
 * @author Alexander Bigga <typo3@slub-dresden.de>
 * @author Tobias Kreße <typo3@slub-dresden.de>
 */
class CollectionsTest extends \TYPO3\TestingFramework\Core\Unit\UnitTestCase
{
    /**
     * @var \Slub\MatomoReporter\Domain\Model\Collections
     */
    protected $subject = null;

    protected function setUp()
    {
        parent::setUp();
        $this->subject = new \Slub\MatomoReporter\Domain\Model\Collections();
    }

    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * @test
     */
    public function getNameReturnsInitialValueForString()
    {
        self::assertSame(
            '',
            $this->subject->getName()
        );
    }

    /**
     * @test
     */
    public function setNameForStringSetsName()
    {
        $this->subject->setName('Conceived at T3CON10');

        self::assertAttributeEquals(
            'Conceived at T3CON10',
            'name',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function getVisitsReturnsInitialValueForVisits()
    {
        $newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        self::assertEquals(
            $newObjectStorage,
            $this->subject->getVisits()
        );
    }

    /**
     * @test
     */
    public function setVisitsForObjectStorageContainingVisitsSetsVisits()
    {
        $visit = new \Slub\MatomoReporter\Domain\Model\Visits();
        $objectStorageHoldingExactlyOneVisits = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
        $objectStorageHoldingExactlyOneVisits->attach($visit);
        $this->subject->setVisits($objectStorageHoldingExactlyOneVisits);

        self::assertAttributeEquals(
            $objectStorageHoldingExactlyOneVisits,
            'visits',
            $this->subject
        );
    }

    /**
     * @test
     */
    public function addVisitToObjectStorageHoldingVisits()
    {
        $visit = new \Slub\MatomoReporter\Domain\Model\Visits();
        $visitsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['attach'])
            ->disableOriginalConstructor()
            ->getMock();

        $visitsObjectStorageMock->expects(self::once())->method('attach')->with(self::equalTo($visit));
        $this->inject($this->subject, 'visits', $visitsObjectStorageMock);

        $this->subject->addVisit($visit);
    }

    /**
     * @test
     */
    public function removeVisitFromObjectStorageHoldingVisits()
    {
        $visit = new \Slub\MatomoReporter\Domain\Model\Visits();
        $visitsObjectStorageMock = $this->getMockBuilder(\TYPO3\CMS\Extbase\Persistence\ObjectStorage::class)
            ->setMethods(['detach'])
            ->disableOriginalConstructor()
            ->getMock();

        $visitsObjectStorageMock->expects(self::once())->method('detach')->with(self::equalTo($visit));
        $this->inject($this->subject, 'visits', $visitsObjectStorageMock);

        $this->subject->removeVisit($visit);
    }
}
