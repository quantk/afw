<?php
declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: quantick
 * Date: 18.10.2018
 * Time: 12:35
 */

namespace Afw\Component\Migration\Console;


use Afw\Component\Migration\Mode;
use Afw\Component\Migration\Service\MigrationStrategyFactory;
use Afw\Component\Migration\Service\Migrator;
use Doctrine\DBAL\Connection;
use Symfony\Component\Console\Command\Command;
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
     * MigrateCommand constructor.
     * @param Connection $connection
     * @param MigrationStrategyFactory $migrationStrategyFactory
     */
    public function __construct(
        Connection $connection,
        MigrationStrategyFactory $migrationStrategyFactory
    )
    {
        parent::__construct();

        $this->connection = $connection;
        $this->migrationStrategyFactory = $migrationStrategyFactory;
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
        $migrationsPath = implode(DIRECTORY_SEPARATOR, [ROOT_DIR, 'app', 'Migrations']);
        $strategy = $this->migrationStrategyFactory->create($mode, $this->connection, $migrationsPath);
        $migrator = new Migrator($strategy, $this->connection, $output);

        $migrator->migrate();
    }

}