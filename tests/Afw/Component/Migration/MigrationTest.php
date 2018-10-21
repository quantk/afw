<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 21.10.2018
 * Time: 12:27
 */

namespace Tests\Afw\Component\Migration;


use Afw\Component\Migration\Migration;
use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;

final class MigrationTest extends TestCase
{
    public function testUp()
    {
        $connection = $this->createMock(Connection::class);
        $migration = new class($connection) extends Migration
        {

            public function up()
            {
                $this->addExpression('sqlUp1');
                $this->addExpression('sqlUp2');
            }

            public function down()
            {
                $this->addExpression('sqlDown1');
                $this->addExpression('sqlDown2');
            }
        };

        $migration->up();
        static::assertAttributeSame(['sqlUp1', 'sqlUp2'], 'statements', $migration);

        $connection->expects(static::exactly(2))->method('exec');
        $migration->execute();
    }

    public function testDown()
    {
        $connection = $this->createMock(Connection::class);
        $migration = new class($connection) extends Migration
        {

            public function up()
            {
                $this->addExpression('sqlUp1');
                $this->addExpression('sqlUp2');
            }

            public function down()
            {
                $this->addExpression('sqlDown1');
                $this->addExpression('sqlDown2');
            }
        };

        $migration->down();
        static::assertAttributeSame(['sqlDown1', 'sqlDown2'], 'statements', $migration);

        $connection->expects(static::exactly(2))->method('exec');
        $migration->execute();
    }
}