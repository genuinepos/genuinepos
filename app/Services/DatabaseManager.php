<?php

namespace App\Services;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\DatabaseManager as BaseDatabaseManager;
use Illuminate\Contracts\Config\Repository;
use Stancl\Tenancy\Contracts\TenantWithDatabase;

class DatabaseManager
{
    protected $app;
    protected $database;
    protected $config;

    public function __construct(Application $app, BaseDatabaseManager $database, Repository $config)
    {
        $this->app = $app;
        $this->database = $database;
        $this->config = $config;
    }

    public function connectToTenant(TenantWithDatabase $tenant)
    {
        $this->purgeTenantConnection();
        $this->createTenantConnection($tenant);
        $this->setDefaultConnection('tenant');
    }

    public function reconnectToCentral()
    {
        $this->purgeTenantConnection();
        $this->setDefaultConnection($this->config->get('tenancy.database.central_connection'));
    }

    protected function setDefaultConnection(string $connection)
    {
        $this->config['database.default'] = $connection;
        $this->database->setDefaultConnection($connection);
    }

    protected function createTenantConnection(TenantWithDatabase $tenant)
    {
        $connectionConfig = $tenant->database()->connection();
        $this->config->set('database.connections.tenant', $connectionConfig);
    }

    protected function purgeTenantConnection()
    {
        if ($this->database->connection('tenant')) {
            $this->database->purge('tenant');
        }

        $this->config->offsetUnset('database.connections.tenant');
    }

    public function ensureTenantCanBeCreated(TenantWithDatabase $tenant): void
    {
        $manager = $tenant->database()->manager();

        if ($manager->databaseExists($database = $tenant->database()->getName())) {
            throw new \Exception("Tenant database already exists: $database");
        }

        if ($manager instanceof \Stancl\Tenancy\Contracts\ManagesDatabaseUsers && $manager->userExists($username = $tenant->database()->getUsername())) {
            throw new \Exception("Tenant database user already exists: $username");
        }
    }
}
