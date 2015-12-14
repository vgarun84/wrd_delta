# wiredDelta arunn Provisioning

## Folder structure for this test app
index.php
config
 - bootstrap.php which having all Initialization of application mode(dev, stage, prd) and autoloader class stack.
includes
 - defines.php all global define variable, check define('SITE_URL', 'https://arunn.wired-delta-dev.com');
 - functions.php
src
 - api
 - classes
   - Forms
      - FormCreator.php class used for generate the form elements of defined elements by array
      - FormValidation.php class used for validate, where rules are defined in array of config

## Virtual Host
<VirtualHost *:443>
    ServerName arunn.wired-delta-dev.com
    DocumentRoot /mnt/work/www/wiredDelta
    #SetEnv WIRED_DELTA_ENVIRONMENT development

    <Directory /mnt/work/www/wiredDelta>
        AllowOverride all
    </Directory>
   SSLEngine on
   SSLCertificateFile /etc/ssl/certs/ssl-cert-snakeoil.pem
   SSLCertificateKeyFile /etc/ssl/private/ssl-cert-snakeoil.key
</VirtualHost>
