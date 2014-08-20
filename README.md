# Database Manager

Database Manager helps developers manage their dev/test/prod databases.

Features:

* Load database connection configuration
* Instantiate PDO or Doctrine DBAL connections
* Update database schema based on schema files
* Load fixtures into your database

Database Manager can be used both as a command-line utility as a PHP library

## Installation

### Composer

Use this installation path if you wish to use Database Manager in your PHP project.

Add the following to your `composer.json` file, in the `require` section:

    "linkorb/database-manager": "dev-master"

Then run `composer update` to install the new dependency

### Git

Use this installation path if you wish to use Database Manager as a stand-alone utility.

Checkout the code from Github:

    git clone git@github.com:linkorb/database-manager.git
    cd database-manager
    composer install # install dependencies
    ./bin/database-manager # list command-line options

## Database configuration files

Database connection information is stored in a simple .ini file. Here's a working example:

    name = mydb
    server = localhost
    username = my_username
    password = my_password

Store your database in `/share/config/database/[databasename].conf`

## Command-line options

`bin/database-manager connection:config [dbname]`

This will load the configuration from `/share/config/database/[databasename].conf`, and display it on the console.

`bin/database-manager database:loadschema <dbname> </path/to/schema.xml> [--apply]`

Load schema from `/path/to/schema.xml`, and update database `dbname`.
Without the `--apply` flag, only changes are displayed. 
When the `--apply` flag is provided, the actual changes are applied

`bin/database-manager database:loadfixture <dbname> </path/to/fixture.yml`

Load the fixture data from `/path/to/fixture.yml`, and add it to `dbname`.

## Contributing

Ready to build and improve on this repo? Excellent!
Go ahead and fork/clone this repo and we're looking forward to your pull requests!
Be sure to update the unit tests in tests/.

If you are unable to implement changes you like yourself, don't hesitate to
open a new issue report so that we or others may take care of it.

## License

Please check LICENSE.md for full license information

## Brought to you by the LinkORB Engineering team

<img src="http://www.linkorb.com/d/meta/tier1/images/linkorbengineering-logo.png" width="200px" /><br />
Check out our other projects at [linkorb.com/engineering](http://www.linkorb.com/engineering).

Btw, we're hiring!
