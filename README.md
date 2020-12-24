# MailBag

![PHPUnit](https://github.com/ssddanbrown/mailbag/workflows/PHPUnit/badge.svg)

MailBag is a simple plaintext-focused email manager. The application has been purpose-built for a fairly simple use-case. Due to limited time, I don't have much of a desire to widen the scope.

## Features

- One-off sends of plaintext emails.
- List management with user-signup form & email confirmation flow.
- List level & contact-level unsubscribe options via links in sends.
- RSS feed sends, sending emails every set number of days with new items from the feed.
- Auto-scheduled unsubscribed contact scrubbing.

## Limitations

- Plain text emails only, No HTML emails.
- No open/click tracking of any kind.
- No scheduled sends.
- Limited built-in customization.
- Requires SMTP service for actual email sending.

## Alternative Apps

Here are some much fuller-featured alternatives to MailBag:

- [phpList](https://www.phplist.com/)
- [Mailcoach](https://mailcoach.app/)
- [listmonk](https://listmonk.app/)
- [Mautic](https://www.mautic.org/)

## Contributing

As said above, Due to limited time I don't want to widen the scope and stack on features, especially with there already being good alternatives as listed above. However, if you find some bugs or can provide fixes to existing functionality feel free to provide them. 

## Attribution & Tech Stack

MailBag is built using the following great projects:

- [Laravel](https://laravel.com)
- [Tailwind](https://tailwindcss.com)
- [CodeMirror](https://codemirror.net/)
- [SQLite](https://sqlite.org/index.html)

## Install

The below outlines the process for install, but it's really intended for those with a little experience with webservers and modern PHP software. It's a fairly involved process. For further help you can try searching for "Laravel install" guides since the process will be much the same due to the app being built upon Laravel.

Some guidance and a nginx example can be seen on the Laravel docs here: https://laravel.com/docs/8.x/deployment#server-requirements.

#### Requirements

MailBag has the following requirements:

- PHP > 7.3
  - Extensions: BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML, SQLite3
- SQLite > 3.30
- [Composer](https://getcomposer.org/)
- Ideally Nginx, Apache or similar kind of server to handling incoming requests.
- SMTP service for sending the emails.
- NodeJS v12+ & NPM

You'll also need command-line access on the host including the ability to configure cron and process management. Using git will help with keeping the codebase versions for managing updates.

#### Initial Install Steps

```shell
# Clone down the project files using git
git clone https://github.com/ssddanbrown/mailbag.git

# cd into the application folder and install the dependencies via composer
composer install --no-dev

# Copy the .env.example file to .env and fill with your own details
cp .env.example .env # (Then go through each option in there)

# Set the application key
php artisan key:generate

# Create the database/database.sqlite file
touch database/database.sqlite

# Migrate the database
php artisan migrate

# Install and build JS/CSS Dependancies
npm install
npm run production

# Check the storage/ and boostrap/cache (and all subfolders) are writable by the webserver, Commands reflect ubuntu common defaults
chown -R www-data:www-data storage/ boostrap/cache/

# Set up your webserver with the root pointing at the `public/` folder. (Nginx "root" or Apache "DocumentRoot"). 
# Done!
```

#### Scheduler Setup

The application has some scheduled tasks such as contact cleanup and RSS feed checking. For this, you need to add a cron entry that runs evey minute like so:

```shell
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

You'll need to change the `path-to-your-project` to be where your mailbag folders are. It'll be wise to setup the cron as someone that'll have permission to read & write the app files, including the folders that you may have changed permissions for to grant the webserver access.

#### Queue Worker Setup

MailBag uses queues for common operations such as sending mail and processing RSS feeds. For very small setups you could get away with not using a queue, but it'd be risky due to various PHP timeouts. 

The Laravel docs have some details in using `supervisor` to run a queue: https://laravel.com/docs/8.x/queues#installing-supervisor

Alternatively you could create a `systemd` service like so:

```
[Unit]
Description=MailBag Queue

[Service]
User=www-data
Group=www-data
Restart=on-failure
ExecStart=/usr/bin/php /path-to-your-project/artisan queue:work --sleep=3 --tries=1

[Install]
WantedBy=multi-user.target
```
