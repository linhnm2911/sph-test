# Jugaad Patches

## Requirements
 - Drupal 8 or 9
 - PHP >=7.3
 
## Installation
- Copy the following into the root `composer.json` file's `repository` key
```bash
"repositories": [
  {
      "type": "package",
      "package": {
        "name": "drupal/jp_product",
        "type": "drupal-module",
        "version": "1.0.0",
        "source": {
          "type": "git",
          "url": "https://github.com/linhnm2911/sph-test.git",
          "reference": "f4ed204c7dab89be4b96d5b9d9d571f84b08866e"
        },
        "require": {
          "bacon/bacon-qr-code": "^2.0",
          "calcinai/php-imagick": "^0.1.2"
        }
      }
  }
]
```
- Run `composer require drupal/jp_product`
- Enable module

## Contributors

[<img src="https://www.drupal.org/files/styles/grid-2-2x-square/public/user-pictures/picture-3611569-1555560579.png" width="50px" />](mailto:nguyenmanhlinhuit2911@gmail.com "Linh Nguyen")
