# Symfony backend assignment
## Setup / installation instructions
### Symfony
This project relies on Symfony for the backend. For instructions go to:
https://symfony.com/doc/current/setup.html

### Database - MariaDB / MySQL
This project is configured to use MariaDB. In the root folder you can find a database dump file from mariaDB.
This should be compatible with MySQL.

Symfony is configured to use MariaDB with these settings:
<ul>
    <li>
        User: root
    </li>
    <li>
        Password: foobar
    </li>
    <li>
        Url: 127.0.0.1
    </li>
    <li>
        Port: 3306
    </li>
    <li>
        DB name: blogsite
    </li>
</ul>

If necessary adjust these settings inside the .env file.
See: https://symfony.com/doc/current/doctrine.html#configuring-the-database

### Mailhog
This project relies on mailhog to send emails. To install mailhog go to:
https://github.com/mailhog/MailHog

The .env file of the server is configured with the default mailhog settings. If needed
please adjust the .env file.

### Tailwind CSS
The frontend is styled with Tailwind CSS. The project should work without further configuration.

In case the front end is not styled, please follow these directions: 
https://tailwindcss.com/docs/guides/symfony

As backup you can also uncomment this script line inside templates\base.html.twig:
&lt;script src="https://cdn.tailwindcss.com"&gt;&lt;/script&gt;

## How to run
Open a console, go to the project directory and run:
symfony server:start

## Login credentials
The example database has three users. These are their credentials:
<ul>
    <li>
        a@a.nl / testtest
    </li>
    <li>
        b@b.nl / testtest
    </li>
    <li>
        m@test.nl / testtest
    </li>
</ul>