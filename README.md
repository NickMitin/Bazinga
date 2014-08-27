Bazinga
=======

## Installer

Установить в папку `/var/project-name`

`composer create-project tbms/bazinga-cms /var/project-name -s dev`

Установить в текушию папку

`composer create-project tbms/bazinga-cms ./ -s dev`

*Папка не должна существовать или папка должна быть пустой*

## Настройка

### Apache

в корне создаем `.htaccess`

```
<IfModule mod_rewrite.c>
    RewriteEngine on
    RewriteRule  ^$ www/generator.php    [L]
    RewriteRule  (.*) www/generator.php$1 [L]
</IfModule>
```
