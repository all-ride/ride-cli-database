# Ride: Database CLI

This module adds various database commands to the Ride CLI.

## Commands

### database

This command shows an overview of the database connections in the manager.

**Syntax**: ```database```

**Alias**: ```db```

### database convert utf8

This command converts all tables from the provided database connection to UTF-8.

**Syntax**: ```database convert utf8 <name>```
- ```<name>```: Name of the connection

**Alias**: ```dbutf8```

### database create

This command creates the database on the server of the connection if it does not exist.

**Syntax**: ```database create [<name> [<charset> [<collation>]]]```
- ```<name>```: Name of the connection
- ```<charset>```: Default charset for the database (default utf8)
- ```<collation>```: Default collation for the database (default utf8_general_ci)

**Alias**: ```dbc```

### database default

This command gets or sets the name of the default database connection.

**Syntax**: ```database default <name>```
- ```<name>```: Name of the connection

**Alias**: ```dbdef```

### database drop

This command drops the database on the server of the connection if it exists.

**Syntax**: ```database drop <name>```
- ```<name>```: Name of the connection

**Alias**: dbdr

### database add

This command registers a database connection in the manager.

**Syntax**: ```database add <name> <dsn>```
- ```<name>```: Name for the connection
- ```<dsn>```: DSN of the connection (protocol://username:password@host:port/database)

**Alias**: ```dba```

### database status

This command gets the status of a connection.

**Syntax**: ```database status [<name>]```
- ```<name>```: Name of the connection

**Alias**: ```dbs```

### database delete

This command unregisters a database connection from the manager.

**Syntax**: ```database delete <name>```
- ```<name>```: Name for the connection

**Alias**: ```dbd```

### database driver

This command shows an overview of the available database drivers.

**Syntax**: ```database driver```

### database query

This command executes a SQL query on the default database connection.

**Syntax**: ```database query [--connection] [<sql>]```
- ```--connection```: Name of the connection to use
- ```<sql>```: The SQL script to execute

**Alias**: ```dbq``` or ```query```

## Related Modules 

- [ride/app](https://github.com/all-ride/ride-app)
- [ride/app-database](https://github.com/all-ride/ride-app-database)
- [ride/cli](https://github.com/all-ride/ride-cli)
- [ride/lib-cli](https://github.com/all-ride/ride-lib-cli)
- [ride/lib-database](https://github.com/all-ride/ride-lib-database)
- [ride/lib-log](https://github.com/all-ride/ride-lib-log)

## Installation

You can use [Composer](http://getcomposer.org) to install this application.

```
composer require ride/cli-database
```
