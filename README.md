# yuncms-collection

The collection module for yuncms.

[![Latest Stable Version](https://poser.pugx.org/yuncms/yuncms-collection/v/stable.png)](https://packagist.org/packages/yuncms/yuncms-collection)
[![Total Downloads](https://poser.pugx.org/yuncms/yuncms-collection/downloads.png)](https://packagist.org/packages/yuncms/yuncms-collection)
[![Build Status](https://img.shields.io/travis/yuncms/yuncms-collection.svg)](http://travis-ci.org/yuncms/yuncms-collection)
[![License](https://poser.pugx.org/yuncms/yuncms-collection/license.svg)](https://packagist.org/packages/yuncms/yuncms-collection)

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```bash
$ composer require yuncms/yuncms-collection
```

or add

```
"yuncms/yuncms-collection": "~2.0.0"
```

to the `require` section of your `composer.json` file.

## Use

```js
/**
 * 发起收藏
 * @param {string} model
 * @param {int} model_id
 * @param callback
 */
function collection(model, model_id, callback) {
    callback = callback || jQuery.noop;
    jQuery.post("/collection/collection/store", {model: model, model_id: model_id}, function (result) {
        return callback(result.status);
    });
}
```

## License

This is released under the MIT License. See the bundled [LICENSE](LICENSE.md)
for details.