<?php

namespace SiestaTest\End2End\Collection;

use Codeception\Util\Debug;
use Siesta\Util\File;
use SiestaTest\End2End\CollectionMany\Generated\Exam;
use SiestaTest\End2End\CollectionMany\Generated\ExamService;
use SiestaTest\End2End\CollectionMany\Generated\ExamUUID;
use SiestaTest\End2End\CollectionMany\Generated\ExamUUIDService;
use SiestaTest\End2End\CollectionMany\Generated\Student;
use SiestaTest\End2End\CollectionMany\Generated\StudentService;
use SiestaTest\End2End\CollectionMany\Generated\StudentUUID;
use SiestaTest\End2End\CollectionMany\Generated\StudentUUIDService;
use SiestaTest\End2End\Util\End2EndTest;

class CollectionManyTest extends End2EndTest
{

    public function setUp(): void
    {
        $silent = true;
        $this->resetSchema();
        $schemaFile = new File(__DIR__ . "/schema/collection.many.test.xml");
        $this->generateSchema($schemaFile, __DIR__, $silent);
    }

    public function testCollectionMany()
    {
        // create NxM relation
        $exam1 = new Exam();
        $exam1->setName("Database Engineering I");

        $exam2 = new Exam();
        $exam2->setName("Database Engineering II");

        $student = new Student();
        $student->setName("Thomas");
        $student->addToExamList($exam1);
        $student->addToExamList($exam2);
        $student->save(true);

        // rest from the other side of the relation
        $studentList = $exam1->getStudentList();
        $this->assertSame(1, sizeof($studentList));
        $this->assertSame("Thomas", $studentList[0]->getName());
        $studentList = $exam2->getStudentList();
        $this->assertSame(1, sizeof($studentList));
        $this->assertSame("Thomas", $studentList[0]->getName());

        // reload student
        $studentService = StudentService::getInstance();
        $studentReloaded = $studentService->getEntityByPrimaryKey($student->getId());
        $this->assertNotNull($studentReloaded);

        $examList = $studentReloaded->getExamList();
        $this->assertSame(2, sizeof($examList));

        // delete assignment by id
        $studentReloaded->deleteFromExamList($exam1->getId());
        $examList = $studentReloaded->getExamList(true);
        $this->assertSame(1, sizeof($examList));

        // delete assignment without id (all)
        $studentReloaded->deleteFromExamList();
        $examList = $studentReloaded->getExamList(true);
        $this->assertSame(0, sizeof($examList));

        // add the 2 exams again
        $studentReloaded->addToExamList($exam1);
        $studentReloaded->addToExamList($exam2);
        $studentReloaded->save(true);

        // delete the exam by id
        $studentReloaded->deleteAssignedExam($exam1->getId());
        $examService = ExamService::getInstance();
        $exam1Reload = $examService->getEntityByPrimaryKey($exam1->getId());
        $this->assertNull($exam1Reload);

        // delete all exam (without id)
        $studentReloaded->deleteAssignedExam();
        $examService = ExamService::getInstance();
        $exam2Reload = $examService->getEntityByPrimaryKey($exam2->getId());
        $this->assertNull($exam2Reload);
    }

    public function testCollectionManyUUID()
    {
        // create NxM relation
        $exam1 = new ExamUUID();
        $exam1->setName("Database Engineering I");

        $exam2 = new ExamUUID();
        $exam2->setName("Database Engineering II");

        $student = new StudentUUID();
        $student->setName("Thomas");
        $student->addToExamList($exam1);
        $student->addToExamList($exam2);
        $student->save(true);

        // rest from the other side of the relation
        $studentList = $exam1->getStudentList();
        $this->assertSame(1, sizeof($studentList));
        $this->assertSame("Thomas", $studentList[0]->getName());
        $studentList = $exam2->getStudentList();
        $this->assertSame(1, sizeof($studentList));
        $this->assertSame("Thomas", $studentList[0]->getName());

        // reload student
        $studentService = StudentUUIDService::getInstance();
        $studentReloaded = $studentService->getEntityByPrimaryKey($student->getId());
        $this->assertNotNull($studentReloaded);

        $examList = $studentReloaded->getExamList();
        $this->assertSame(2, sizeof($examList));

        // delete assignment by id
        $studentReloaded->deleteFromExamList($exam1->getId());
        $examList = $studentReloaded->getExamList(true);
        $this->assertSame(1, sizeof($examList));

        // delete assignment without id (all)
        $studentReloaded->deleteFromExamList();
        $examList = $studentReloaded->getExamList(true);
        $this->assertSame(0, sizeof($examList));

        // add the 2 exams again
        $studentReloaded->addToExamList($exam1);
        $studentReloaded->addToExamList($exam2);
        $studentReloaded->save(true);

        // delete the exam by id
        $studentReloaded->deleteAssignedExamUUID($exam1->getId());
        $examService = ExamUUIDService::getInstance();
        $exam1Reload = $examService->getEntityByPrimaryKey($exam1->getId());
        $this->assertNull($exam1Reload);

        // delete all exam (without id)
        $studentReloaded->deleteAssignedExamUUID();
        $examService = ExamUUIDService::getInstance();
        $exam2Reload = $examService->getEntityByPrimaryKey($exam2->getId());
        $this->assertNull($exam2Reload);
    }
}