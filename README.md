ZF2 Skeleton App
================

Created by Neilime

NOTE : This module is in heavy development, it's not usable yet.

Introduction
------------

Easy to start a new application

Installation
------------

###### Using Composer (recommended)

Clone the repository and manually invoke `composer` using the shipped
`composer.phar`:

    cd my/project/dir
    git clone git://github.com/neilime/zf2-skeleton-app.git
    cd zf2-skeleton-app
    php composer.phar self-update
    php composer.phar install

(The `self-update` directive is to ensure you have an up-to-date `composer.phar`
available.)

Another alternative for downloading the project is to grab it via `curl`, and
then pass it to `tar`:

    cd my/project/dir
    curl -#L https://github.com/neilime/zf2-skeleton-app/tarball/master | tar xz --strip-components=1

You would then invoke `composer` to install dependencies per the previous
example.

###### Using Git submodules

Alternatively, you can install using native git submodules:

    git clone git://github.com/neilime/zf2-skeleton-app.git --recursive

###### Virtual Host

Afterwards, set up a virtual host to point to the public/ directory of the
project and you should be ready to go!

###### Configuration

Edit config/autoload/local.php file to set your own configuration

In app features
------------

###### Templating : 

* Build complex layouts

###### Assets manager :

* Bundeling
* Compress / optimize
* Css, Js, Less

######  Actions Logger :

* Http Requests
* Insert, update, delete rows in database

###### Database : 

* TableGateway
* RowGateway

###### Messenger :

* Html Email
* Attachements
* Templating

###### User :

* Two step registration
* Multi registration adapters (Database & Social by default)
* Password recovring
* Account management

###### Commons

* Social integration
* CkEditor

###### Js

* Autoloading controllers
* View helper facilities (Url, translate...)
* Ajax loading improvements (views / form submissions)
