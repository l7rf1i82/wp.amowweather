# Wordpress weather widget demo
Show current weather forecast from https://api.openweathermap.org

## Requirements:
- php ^7.4
- node.js ^18.0
- wordpress ^6.0

## Installation
### in bash:
- cd wp-content/plugins
- git clone https://github.com/l7rf1i82/wp.amowweather ./amowweather
- cd amowweather
- npm install
- composer install
- create file `credentials.php`:
  ```php
    <?php
    
    const IPDATA_API_KEY          = 'KEY';
    const OPENWEATHERMAP_API_KEY  = 'KEY';
  ```
  replace `KEY` to value from email

## Usage
- open you_wordpress_installations_hostname/wp-admin/plugins.php
- click to Activate plugin
- open you_wordpress_installations_hostname/wp-admin/index.php
- click to checkbox "Show on public pages"
- open any public page, and check widget in left top screen corner