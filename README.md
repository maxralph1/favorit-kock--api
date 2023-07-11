# Favorit-Kock Restaurant and Food Ordering and Delivery Application

Guide to (usage and description of) the Favorit-Kock application.

**Table of Contents**

1. [What Favorit-Kock Is](#what-is-favorit-kock)
2. [Features of the Application](#features-of-the-application)
3. [Technical Features of the Application](#technical-features-of-the-application)
4. [Technologies Utilized in Crafting Favorit-Kock](#technologies-utilized-in-crafting-favorit-kock)
5. [API Documentation](#api-documentation)
6. [Database Structure](#database-structure)
7. [How to Install and Run the Favorit-Kock Application Locally On Your Device](#how-to-install-and-run-the-favorit-kock-application-locally-on-your-device)
      1. [Requirements](#requirements)
      2. [Installation Procedure](#installation-procedure)
8. [Footnotes !important](#footnotes-important)

## What Is Favorit-Kock?

Favorit-Kock is a restaurant/bar food ordering and delivery application where users can order (pay online for) food and have it delivered to their doorsteps; administrators can add events to be booked.

The complete (server-side and client-side) application can be seen on [Favorit Kock GitHub link](https://github.com/maxralph1/favorit-kock)

## Features of the application

1. There are 4 roles: generic user, rider, admin and superadmin. These roles have different levels of authorization.
2. All user roles can register and add images to their profile.
3. Users can order and pay for food online.
4. Riders can deliver food to users location  based on user orders.
5. Admin can attend to users queries from their level of authorization.
6. Superadmin have a higher authorization with more capabilities.

## Technical Features of the Application

1. Authentication
2. Authorization (multiple roles)
3. Access tokens
4. Soft-delete function so that data is never really lost (all deleted data can be retrieved/re-activated)
5. Laravel Pint for code uniformity and consistency
6. Accessors
7. Scopes
8. API Resources for custom JSON responses
9. Observers
10. Custom Middleware
11. Eager-loading
12. Customized Exception Handlers (a security step for masking the actual model names on the server from API consumers on the client)
13. All incoming requests are validated multiple times on both client side and server-side.
14. API requests rate-limiting to prevent attacks.
15. Other security measures in place to prevent attacks.
16. Multiple error handlers (for both planned and unplanned errors) to catch all errors properly.

Plus much more...

## Technologies Utilized in Crafting Favorit-Kock

Favorit-Kock is crafted in the following programming languages/frameworks and technologies:

1. **Laravel (PHP)** on the server-side.
      1. **Laravel Tests** (for writing comprehensive tests for the application)
2. **MYSQL** (for database).
3. **Cloudinary** for image uploads.
4. **Scribe** for local API documentation.
5. **Postman** for online API documentation.

## API Documentation

Here are links to the API documentation. You may wish to view the online version if you do not want to install and run the application locally on your device:

Online version: https://documenter.getpostman.com/view/13239911/2s93zFXz3G

Offline version: http://localhost/docs

("localhost" here stands for your local development environment port. Laravel by default runs on localhost:8000. So you would typically view the docs on http://localhost:8000/docs unless you decided to run on a port you set by yourself)

## Database Structure

![](./favorit_kock_database_schema.png)

## How to Install and Run the Favorit-Kock Application Locally On Your Device

### Requirements:

1. You must have PHP installed on your device. Visit the [official PHP website](https://www.php.net/) and follow the steps for download and installation.

2. After installing PHP, download and install a text editor (e.g. [VS Code](https://code.visualstudio.com/Download)) if you do not have one.

### Installation procedure:

Then go to your terminal and follow these steps:

1. From your terminal, cd (change directory) into your favorite directory (folder) where you would like to have the application files

```
cd C:\Users\maxim\Desktop>
```

Here I changed directory into my personal Desktop space/folder on my Windows Computer. And then;

2. Clone this repository from here on Github using either of the 2 commands on your terminal:

```
git clone https://github.com/maxralph1/favorit-kock.git
```

or

```
git clone git@github.com:maxralph1/favorit-kock.git
```

3. And to end this section, CD into the newly installed "favorit-kock" application file with the following commands.

```
cd favorit-kock
```

4. From here, use the command below to install all dependencies I utilized in this application as can be seen from my 'server/composer.json' file

```
composer install
```

5. Spin up the server with the command:

```
php artisan serve
```

Your server traditionally starts on port 8000 (http://localhost:8000), if you have nothing currently running this port.

6. Go to the 'server/.env' file which you must have gotten from modifying the 'server/env.example' file and make sure the database name is what you want it to be.

7. You should already have a MySQL database installed and running. Create a database instance with same name as that for the database above. I use XAMPP (you can [get XAMPP here](https://www.apachefriends.org/download.html)). It makes it easier for me.

8. Go back to your server terminal and run the command to migrate and seed your database:

```
php artisan migrate --seed
```

## Footnotes !important

This application is strictly for demonstration purposes. It is not yet production-ready and should never be used as such.I will continue to gradually update this project in my free time.

\*\*On the general pattern of coding, if you noticed, the entire application has a specific approach in structure. I however decided to deviate (bring in several flavors) a little (but paid attention not to deviate too much) from these principles so as to showcase my skills.

In a real-world application, I would stick to a particular pattern.

Finally, contributions/suggestions on how to improve this project, are welcome.
