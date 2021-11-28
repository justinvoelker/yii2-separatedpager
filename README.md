# Separated Pager for Yii2

A Yii2 LinkPager that displays the first and last pages inline with other pages.

Rather than having dedicated "First" and "Last" buttons in your LinkPager, **Separated Pager** will show a standard set of page links but will also always include the first and last pages as the first and last page links. No more dedicated first/last buttons and no more guessing how many pages there are.

![sample](https://cloud.githubusercontent.com/assets/2441889/6312491/6a89be10-b948-11e4-9ac8-bcd793664e1a.png)

## Installation

### Install the extension

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist justinvoelker/yii2-separatedpager "*"
```

or add

```
"justinvoelker/yii2-separatedpager": "*"
```

to the require section of your `composer.json` file.

## Usage

Simply add the `pager` property to your GridView and reference this class

```php
GridView::widget([
    'dataProvider' => $dataProvider,
    ...
    'pager' => [
        'class' => 'justinvoelker\separatedpager\LinkPager',
    ]
]);
```

Please note that specifying less than 7 pages won't produce useful results.  Less than 7 pages of content is acceptable (will look like the standard LinkPager) but limiting the pager to something less than 7 pages will look and work poorly.  Five pages and the pager is almost worthless.  Less than 5 and it is worthless. 

## Available Options

In addition to all of the standard [LinkPager](www.yiiframework.com/doc-2.0/yii-widgets-linkpager.html) properties, one new property has been added, `separator`, that specifies a string to be used to indicate that multiple pages are being omitted.  The default separator is `...`.

Here is my preferred pager setup.  A max of 7 pages, 'Previous' and 'Next' buttons spelled out that are hidden on extra-small screens.

```php
    'pager' => [
        'class' => 'justinvoelker\separatedpager\CustomLinkPager',
        'maxButtonCount' => 7,
        'prevPageLabel' => 'Previous',
        'nextPageLabel' => 'Next',
        'prevPageCssClass' => 'prev hidden-xs',
        'nextPageCssClass' => 'next hidden-xs',
        'activePageAsLink' => false,
    ]
```

Keep in mind that setting css classes will overwrite the defaults rather than appending to them.  If additional classes should be included (such as the 'hidden-xs' above) the original `prev` and `next` should be included as well (this is the same way the standard LinkPager functions).

Standard LinkPager functionality uses a link for the active page. By setting `activePageAsLink` to false the link can be replaced with a span that looks the same but cannot be clicked. 
