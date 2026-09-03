<?php

declare(strict_types=1);

use Stancl\Tenancy\Database\Models\Domain;
// use Stancl\Tenancy\Database\Models\Tenant;

return [
    'tenant_model' => \App\Models\Central\Tenant::class,
    'id_generator' => Stancl\Tenancy\UUIDGenerator::class,

    'domain_model' => Domain::class,

    /**
     * La lista de dominios que alojan tu aplicación central.
     *
     * Solo es relevante si usas el middleware de identificación por dominio o subdominio.
     */
    'central_domains' => [
        'ssr.test',        
        // '127.0.0.1',
        // 'localhost',
    ],

    /**
     * Los bootstrappers de tenancy se ejecutan cuando se inicializa la tenancy.
     * Su responsabilidad es hacer que las funcionalidades de Laravel sean compatibles con multi-tenant.
     *
     * Para configurar su comportamiento, revisa las claves de configuración más abajo.
     */
    'bootstrappers' => [
        Stancl\Tenancy\Bootstrappers\DatabaseTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\CacheTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\FilesystemTenancyBootstrapper::class,
        Stancl\Tenancy\Bootstrappers\QueueTenancyBootstrapper::class,
        // Stancl\Tenancy\Bootstrappers\RedisTenancyBootstrapper::class, // Nota: se necesita phpredis
        App\Tenancy\Bootstrappers\PermissionCacheBootstrapper::class,
    ],

    /**
     * Configuración de tenancy para base de datos. Usado por DatabaseTenancyBootstrapper.
     */
    'database' => [
        'central_connection' => env('DB_CONNECTION', 'central'),

        /**
         * Conexión usada como "plantilla" para la conexión de base de datos del tenant creada dinámicamente.
         * Nota: no nombres tu conexión plantilla "tenant", ese nombre está reservado por el paquete.
         */
        'template_tenant_connection' => null,

        /**
         * Los nombres de las bases de datos de los tenants se crean así:
         * prefijo + tenant_id + sufijo.
         */
        'prefix' => 'tenant_',
        'suffix' => '',

        /**
         * Los TenantDatabaseManagers son clases que manejan la creación y eliminación de bases de datos de tenants.
         */
        'managers' => [
            'sqlite' => Stancl\Tenancy\TenantDatabaseManagers\SQLiteDatabaseManager::class,
            'mysql' => Stancl\Tenancy\TenantDatabaseManagers\MySQLDatabaseManager::class,
            'mariadb' => Stancl\Tenancy\TenantDatabaseManagers\MySQLDatabaseManager::class,
            'pgsql' => Stancl\Tenancy\TenantDatabaseManagers\PostgreSQLDatabaseManager::class,

        /**
         * Usa este gestor de base de datos para MySQL si quieres crear un usuario de BD por cada base de datos de tenant.
         * Puedes personalizar los permisos otorgados a estos usuarios cambiando la propiedad $grants.
         */
            // 'mysql' => Stancl\Tenancy\TenantDatabaseManagers\PermissionControlledMySQLDatabaseManager::class,

        /**
         * Deshabilita el gestor pgsql de arriba y habilita el de abajo si quieres
         * separar las BDs de los tenants por esquemas en lugar de por bases de datos.
         */
            // 'pgsql' => Stancl\Tenancy\TenantDatabaseManagers\PostgreSQLSchemaManager::class, // Separar por esquema en lugar de base de datos
        ],
    ],

    /**
     * Configuración de tenancy para caché. Usado por CacheTenancyBootstrapper.
     *
     * Funciona para todas las llamadas al facade Cache, al helper cache()
     * y llamadas directas a stores de caché inyectados.
     *
     * Cada clave en caché tendrá un tag aplicado. Este tag se usa para
     * delimitar el alcance de la caché tanto al escribir como al leer.
     *
     * Puedes limpiar la caché selectivamente especificando el tag.
     */
    'cache' => [
        'tag_base' => 'tenant', // Este tag_base, seguido del tenant_id, formará un tag que se aplicará en cada llamada a caché.
    ],

    /**
     * Configuración de tenancy para sistema de archivos. Usado por FilesystemTenancyBootstrapper.
     * https://tenancyforlaravel.com/docs/v3/tenancy-bootstrappers/#filesystem-tenancy-boostrapper.
     */
    'filesystem' => [
        /**
         * Cada disco listado en el array 'disks' tendrá como sufijo el suffix_base seguido del tenant_id.
         */
        'suffix_base' => 'tenant',
        'disks' => [
            'local',
            'public',
            // 's3',
        ],

        /**
         * Usa esto para discos locales.
         *
         * Ver https://tenancyforlaravel.com/docs/v3/tenancy-bootstrappers/#filesystem-tenancy-boostrapper
         */
        'root_override' => [
            // Discos cuyas raíces deben sobreescribirse después de agregar el sufijo a storage_path().
            'local' => '%storage_path%/app/',
            'public' => '%storage_path%/app/public/',
        ],

        /**
         * Indica si storage_path() debe tener sufijo.
         *
         * Nota: Deshabilitar esto probablemente romperá la tenancy en disco local. Solo deshabilítalo
         * si usas un servicio externo de almacenamiento de archivos como S3.
         *
         * Para la gran mayoría de aplicaciones esta funcionalidad debe estar habilitada. Pero en algunos
         * casos extremos puede causar problemas (como usar Passport con Vapor - ver #196), por lo que
         * puedes deshabilitarlo si experimentas estos problemas.
         */
        'suffix_storage_path' => true,

        /**
         * Por defecto, las llamadas a asset() también son multi-tenant. Puedes usar global_asset() y mix()
         * para assets globales no específicos de un tenant. Sin embargo, podrías tener algunos problemas al usar
         * paquetes que hacen llamadas a asset() dentro de la app del tenant. Para evitar estos problemas, puedes
         * deshabilitar la tenancy del helper asset() y usar explícitamente tenant_asset() en los lugares
         * donde quieras usar assets específicos del tenant (imágenes de productos, avatares, etc).
         */
        'asset_helper_tenancy' => false,
    ],

    /**
     * Configuración de tenancy para Redis. Usado por RedisTenancyBootstrapper.
     *
     * Nota: Necesitas phpredis para usar la tenancy con Redis.
     *
     * Nota: No necesitas esto si solo usas Redis para caché.
     * La tenancy de Redis solo es relevante si haces llamadas directas a Redis,
     * ya sea usando el facade Redis o inyectándolo como dependencia.
     */
    'redis' => [
        'prefix_base' => 'tenant', // Cada clave en Redis tendrá este prefix_base como prefijo, seguido del tenant_id.
        'prefixed_connections' => [ // Conexiones Redis cuyas claves tienen prefijo, para separar las claves de un tenant de otro.
            // 'default',
        ],
    ],

    /**
     * Las features son clases que proveen funcionalidades adicionales
     * que no son necesarias para inicializar la tenancy. Se ejecutan
     * independientemente de si la tenancy ha sido inicializada.
     *
     * Revisa la página de documentación de cada clase para
     * entender cuáles quieres habilitar.
     */
    'features' => [
        // Stancl\Tenancy\Features\UserImpersonation::class,
        // Stancl\Tenancy\Features\TelescopeTags::class,
         Stancl\Tenancy\Features\UniversalRoutes::class,
        // Stancl\Tenancy\Features\TenantConfig::class, // https://tenancyforlaravel.com/docs/v3/features/tenant-config
        // Stancl\Tenancy\Features\CrossDomainRedirect::class, // https://tenancyforlaravel.com/docs/v3/features/cross-domain-redirect
        // Stancl\Tenancy\Features\ViteBundler::class,
    ],

    /**
     * Indica si las rutas de tenancy deben registrarse.
     *
     * Las rutas de tenancy incluyen las rutas de assets del tenant. Por defecto esta ruta
     * está habilitada. Puede ser útil deshabilitarlas si usas almacenamiento externo
     * (ej. S3 / Dropbox) o tienes un controlador de assets personalizado.
     */
    'routes' => true,

    /**
     * Parámetros usados por el comando tenants:migrate.
     */
    'migration_parameters' => [
        '--force' => true, // Debe ser true para ejecutar migraciones en producción.
        '--path' => [database_path('migrations/tenant')],
        '--realpath' => true,
    ],

    /**
     * Parámetros usados por el comando tenants:seed.
     */
    'seeder_parameters' => [

         /* '--class' => 'DatabaseSeeder', // root seeder class */
        '--class' => Database\Seeders\Tenant\TenantDatabaseSeeder::class, // root seeder class
        // '--force' => true, // Debe ser true para ejecutar seeders de tenants en producción
    ],
];