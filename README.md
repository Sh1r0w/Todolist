### Installation of version 6.4.8 for Todolist

Welcome to the updated version of Todolist based on the latest LTS of Symfony.

Here is the installation procedure:

#### 1 - Replacing the old version

You will need to delete the old Todolist folder and replace it with this one.

**Warning**: Do not delete the database.

#### 2 - Database creation

In the `.env` file, from lines 26 to 29, you have the choice of your database.

To use MySQL, uncomment line 27 by removing the `#` and add `#` to line 29.

Edit line 27:

DATABASE_URL="mysql://app:!ChangeMe!@127.0.0.1:3306/app?serverVersion=dbmaria-10.4.8&charset=utf8mb4"

The first `app` after `:` and before `@` corresponds to the database login, and after `@` corresponds to the password.

For example:

DATABASE_URL="mysql://root@127.0.0.1:3306/td_todolist?serverVersion=dbmaria-10.4.8&charset=utf8mb4"


#### 3 - Bundle installation

After copying, type `composer u` to proceed with the installation of the version bundles.

#### 4 - Adding fixtures

To configure the existing tasks for the anonymous user, you will need to load the fixtures with the following command:


"php bin/console doctrine:fixtures:load --append"


(`--append` means that we will not replace the data in the database)

#### 5 - Database migration

To add the foreign key to the tasks that will link them to the users, you need to migrate the database with these commands:

**Synchronize metadata**


"php bin/console doctrine:migrations:sync-metadata-storage"


**Create the migration file:**


"php bin/console doctrine:migrations:diff"


This will create a file in `root\migrations\Version1246541314513.php` (the numbers are just an example).

In this file, you need to add the following line:


"$this->addSql('UPDATE task SET user_id = (SELECT id FROM user WHERE user.username = "Anonyme")');""


right after the creation of the `user_id` table:


"$this->addSql('ALTER TABLE task ADD user_id INT NOT NULL');"


This will link the already created tasks to the user "Anonyme".

#### 6 - Launching the API

Type the following in the terminal to start the API:


"symfony server:start"
