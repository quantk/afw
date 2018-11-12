<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 18.10.2018
 * Time: 22:32
 */

namespace Afw\Component\Migration\Console;


use Afw\Component\Console\Command;
use Afw\Component\Migration\Migration;
use Nette\PhpGenerator\ClassType;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CreateCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('db:migration:create')
            ->setDescription('Create migration')
            ->setHelp('This command allows you to create migrate');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $migrationsPath = $this->getContainer()->get('migration.path');
        if (null === $migrationsPath) {
            throw new \RuntimeException('Migration path not set in parameters.php');
        }

        $dateNow = new \DateTimeImmutable();
        $dateString = $dateNow->format('d_j_Y_G_i_s');

        $classname = sprintf('Migration%s', $dateString);
        $newMigrationClass = new ClassType($classname);
        $newMigrationClass->addExtend('\\' . Migration::class);
        $newMigrationClass
            ->addMethod('up')
            ->setVisibility('public');
        $newMigrationClass
            ->addMethod('down')
            ->setVisibility('public');

        $fileTemplate = <<<PHPFILE
<?php
declare(strict_types=1);

namespace App\Migrations;
    
$newMigrationClass
PHPFILE;

        file_put_contents($migrationsPath . DIRECTORY_SEPARATOR . $classname . '.php', $fileTemplate);
    }
}