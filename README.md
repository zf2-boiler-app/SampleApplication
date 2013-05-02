ZF2 BoilerApp Sample application
================

NOTE : This module is in heavy development, it's not usable yet.

Introduction
------------

Easy to start a new application

Installation
------------

###### Using Composer (recommended)

Clone the repository and manually invoke `composer` using the shipped `composer.phar`:
```ssh
cd my/project/dir
git clone git://github.com/neilime/zf2-skeleton-app.git
cd zf2-skeleton-app
php composer.phar self-update
php composer.phar install
```

(The `self-update` directive is to ensure you have an up-to-date `composer.phar` available.)

Another alternative for downloading the project is to grab it via `curl`, and then pass it to `tar`:
```ssh
cd my/project/dir
curl -#L https://github.com/neilime/zf2-skeleton-app/tarball/master | tar xz --strip-components=1
```

You would then invoke `composer` to install dependencies per the previous example.

###### Using Git submodules

Alternatively, you can install using native git submodules:
```ssh
git clone git://github.com/neilime/zf2-skeleton-app.git --recursive
```

###### Virtual Host

Afterwards, set up a virtual host to point to the public/ directory of the
project and you should be ready to go!

###### Configuration

Edit config/autoload/local.php file to set your own configuration

##### Database

Create databas shema (ensure that "sample-application" database exists)
	
```ssh
cd my/project/dir
.\vendor\bin\doctrine-module.bat orm:schema-tool:create
```
