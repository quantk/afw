<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 18.10.2018
 * Time: 12:35
 */

namespace Afw\Component\Migration\Console;


use Afw\Component\Migration\Migration;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\File\File;

final class MigrateCommand extends Command
{
    public const UP_MODE = 'up';
    public const DOWN_MODE = 'down';
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(
        Connection $connection
    )
    {
        parent::__construct();

        $this->connection = $connection;
    }

    protected function configure()
    {
        $this
            ->setName('db:migrate')
            ->setDescription('Migrate migrates')
            ->setHelp('This command allows you to migrate')
            ->addArgument('mode', InputOption::VALUE_REQUIRED, 'up or down', self::UP_MODE);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mode = $input->getArgument('mode');
        if (!in_array($mode, [self::UP_MODE, self::DOWN_MODE])) {
            throw new \RuntimeException('Unexpected mode');
        }
        $migrationsPath = implode(DIRECTORY_SEPARATOR, [ROOT_DIR, 'app', 'Migrations']);
        $migrations = array_filter(scandir($migrationsPath), function ($migrationFile) {
            return !in_array($migrationFile, ['.', '..']);
        });
        foreach ($migrations as $migration) {
            $file = new File($migrationsPath . DIRECTORY_SEPARATOR . $migration);
            $className = $this->getClassNameFromFile($file);
            $migrationClass = "\\App\\Migrations\\${className}";
            /** @var Migration $migration */
            $migration = new $migrationClass($this->connection, $output);
            $migration->{$mode}();

            $migration->execute();
        }
    }

    private function getClassNameFromFile(File $file)
    {
        $filename = $file->getFilename();
        $pathInfo = pathinfo($filename);
        return $pathInfo['filename'];
    }
}