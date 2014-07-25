# Video Hoster Module

## Introduction

### Video Hosting Platform

### Description

I've been thinking lately of creating an app/site for hosting videos, like laracasts et al. However if there's already an open source one available there's no point in creating another. So, does anyone know of one?

I think it'd be a blog basically with extra features for handling purchases and subscriptions. I figure I should write it in ZF2 as that's what my videos are about. But maybe I should use Laravel instead.

### Features

- Standard blog features (name, description, categories, duration, download, publish date, description, meta tags, extract, payment requirement (free/paid) etc, level/complexity, series/standalone)
- Comments handled by Disqus
- Supports markdown format
- Handles purchases and payments (like [Laravel Cashier](https://github.com/laravel/cashier))
- Supports simple static pages
- Social media plugins
- RSS feed
- robots.txt
- sitemap.xml

### Pages/Schema/Sitemap

- home
- FAQ
- testimonials
- about
- RSS
- customer support

### Research

- [Markdown package](https://github.com/maglnet/MaglMarkdown)
- Users (based off of ZfcUser/ZfcBase)
- Email ([SlmMail](https://github.com/juriansluiman/SlmMail) using [Mandrill](https://www.mandrill.com/))
- Analytics ([SlmGoogleAnalytics](https://github.com/juriansluiman/SlmGoogleAnalytics))
- Static Assets ([Assetic](https://github.com/kriswallsmith/assetic))

### To Find

- Payments module (may need to write one or port [Laravel Cashier](https://github.com/laravel/cashier))
- Video integration (I'm thinking of something that makes it easy to embed videos, but start with Wistia specifically)

## Deployment


## Installation


### Using Composer (recommended)


## Web Server Setup


### PHP CLI Server

The simplest way to get started if you are using PHP 5.4 or above is to start the internal PHP cli-server in the root directory:

    php -S 0.0.0.0:8080 -t public/ public/index.php

This will start the cli-server on port 8080, and bind it to all network
interfaces.

**Note: ** The built-in CLI server is *for development only*.

### Apache Setup

To setup apache, setup a virtual host to point to the public/ directory of the
project and you should be ready to go! It should look something like below:

    <VirtualHost *:80>
        ServerName zf2-tutorial.localhost
        DocumentRoot /path/to/zf2-tutorial/public
        SetEnv APPLICATION_ENV "development"
        <Directory /path/to/zf2-tutorial/public>
            DirectoryIndex index.php
            AllowOverride All
            Order allow,deny
            Allow from all
        </Directory>
    </VirtualHost>
