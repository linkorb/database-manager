# Database Manager

Database Manager helps developers manage their dev/test/prod databases.

Features:

* Load database connection configuration
* Instantiate PDO connections

Database Manager can be used both as a command-line utility as a PHP library.

## Suggestions

- https://github.com/dbtk/schema-loader - to load schemas
- https://github.com/linkorb/haigha - to load alice fixtures

## Installation

### Composer

Use this installation path if you wish to use Database Manager in your PHP project.

Add the following to your `composer.json` file, in the `require` section:

    "linkorb/database-manager": "~2.0"

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
    port = 3306
    username = my_username
    password = my_password

Note that `port` key is optional.

Store your database in `/share/config/database/[databasename].conf`

## Command-line options

`bin/database-manager connection:config [dbname]`

This will load the configuration from `/share/config/database/[databasename].conf`, and display it on the console.

## Examples

Please refer to the `examples/` directory for:

* Example database .conf file

## Testing

Install phpunit and copy `phpunit.xml.dist` to `phpunit.xml`:

```bash
cp phpunit.xml.dist phpunit.xml
```

Type `phpunit` in terminal to run tests.

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
