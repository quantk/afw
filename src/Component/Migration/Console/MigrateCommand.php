<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 18.10.2018
 * Time: 12:35
 */

namespace Afw\Component\Migration\Console;


use Afw\Component\Console\Command;
use Afw\Component\Filesystem\Filesystem;
use Afw\Component\Migration\Mode;
use Afw\Component\Migration\Service\MigrationStrategyFactory;
use Afw\Component\Migration\Service\Migrator;
use Afw\Component\Reflection\Reflector;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class MigrateCommand extends Command
{
    public const UP_MODE = 'up';
    public const DOWN_MODE = 'down';
    /**
     * @var Connection
     */
    private $connection;
    /**
     * @var MigrationStrategyFactory
     */
    private $migrationStrategyFactory;
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var Reflector
     */
    private $reflector;

    /**
     * MigrateCommand constructor.
     * @param Connection $connection
     * @param MigrationStrategyFactory $migrationStrategyFactory
     * @param Filesystem $filesystem
     * @param Reflector $reflector
     */
    public function __construct(
        Connection $connection,
        MigrationStrategyFactory $migrationStrategyFactory,
        Filesystem $filesystem,
        Reflector $reflector
    )
    {
        parent::__construct();

        $this->connection = $connection;
        $this->migrationStrategyFactory = $migrationStrategyFactory;
        $this->filesystem = $filesystem;
        $this->reflector = $reflector;
    }

    protected function configure()
    {
        $this
            ->setName('db:migration:migrate')
            ->setDescription('Migrate migrates')
            ->setHelp('This command allows you to migrate')
            ->addArgument('mode', InputOption::VALUE_REQUIRED, 'up or down', Mode::NEXT_MODE);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     * @throws \Doctrine\DBAL\DBALException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $mode = new Mode($input->getArgument('mode'));
        $migrationsPath = $this->getContainer()->get('migration.path');
        if (null === $migrationsPath) {
            throw new \RuntimeException('Migration path not set in parameters.php');
        }
        $migrationsNamespace = $this->getContainer()->get('migration.namespace');
        if (null === $migrationsNamespace) {
            throw new \RuntimeException('Migration namespace not set in parameters.php');
        }
        $strategy = $this->migrationStrategyFactory->create(
            $mode,
            $this->connection,
            $this->filesystem,
            $this->reflector,
            $migrationsPath,
            $migrationsNamespace
        );
        $migrator = new Migrator($strategy, $this->connection, $output);

        $migrator->migrate();
    }

}