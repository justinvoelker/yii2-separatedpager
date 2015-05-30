<?php

namespace justinvoelker\separatedpager;

use Yii;
use yii\helpers\Html;

/**
 * LinkPager displays a list of hyperlinks that lead to different pages of target.
 *
 * LinkPager works with a [[Pagination]] object which specifies the totally number
 * of pages and the current page number.
 *
 * Note that LinkPager only generates the necessary HTML markups. In order for it
 * to look like a real pager, you should provide some CSS styles for it.
 * With the default configuration, LinkPager should look good using Twitter Bootstrap CSS framework.
 *
 * separatedpager changes the default LinkPager behavior by always displaying the first and last
 * pages separated from the current pages by an ellipsis (or any other string specified).
 *
 * @author Justin Voelker <justin@justinvoelker.com>
 */
class LinkPager extends \yii\widgets\LinkPager
{
    /**
     * @var string the name of the input checkbox input fields. This will be appended with `[]` to ensure it is an array.
     */
    public $separator = '...';

    /**
     * @var boolean turns on|off the <a> tag for the active page. Defaults to true (will be a link).
     */
    public $activePageAsLink = true;

    /**
     * @inheritdoc
     */
    protected function renderPageButtons()
    {
        $pageCount = $this->pagination->getPageCount();
        if ($pageCount < 2 && $this->hideOnSinglePage) {
            return '';
        }

        $buttons = [];
        $currentPage = $this->pagination->getPage();

        // first page
        if ($this->firstPageLabel !== false) {
            $buttons[] = $this->renderPageButton($this->firstPageLabel, 0, $this->firstPageCssClass, $currentPage <= 0,
                false);
        }

        // prev page
        if ($this->prevPageLabel !== false) {
            if (($page = $currentPage - 1) < 0) {
                $page = 0;
            }
            $buttons[] = $this->renderPageButton($this->prevPageLabel, $page, $this->prevPageCssClass,
                $currentPage <= 0, false);
        }

        // page calculations
        list($beginPage, $endPage) = $this->getPageRange();
        $startSeparator = false;
        $endSeparator = false;
        $beginPage++;
        $endPage--;
        if ($beginPage != 1) {
            $startSeparator = true;
            $beginPage++;
        }
        if ($endPage + 1 != $pageCount - 1) {
            $endSeparator = true;
            $endPage--;
        }

        // smallest page
        $buttons[] = $this->renderPageButton(1, 0, null, false, 0 == $currentPage);

        // separator after smallest page
        if ($startSeparator) {
            $buttons[] = $this->renderPageButton($this->separator, null, null, true, false);
        }
        // internal pages
        for ($i = $beginPage; $i <= $endPage; ++$i) {
            if ($i != 0 && $i != $pageCount - 1) {
                $buttons[] = $this->renderPageButton($i + 1, $i, null, false, $i == $currentPage);
            }
        }
        // separator before largest page
        if ($endSeparator) {
            $buttons[] = $this->renderPageButton($this->separator, null, null, true, false);
        }
        // largest page
        $buttons[] = $this->renderPageButton($pageCount, $pageCount - 1, null, false,
            $pageCount - 1 == $currentPage);

        // next page
        if ($this->nextPageLabel !== false) {
            if (($page = $currentPage + 1) >= $pageCount - 1) {
                $page = $pageCount - 1;
            }
            $buttons[] = $this->renderPageButton($this->nextPageLabel, $page, $this->nextPageCssClass,
                $currentPage >= $pageCount - 1, false);
        }

        // last page
        if ($this->lastPageLabel !== false) {
            $buttons[] = $this->renderPageButton($this->lastPageLabel, $pageCount - 1, $this->lastPageCssClass,
                $currentPage >= $pageCount - 1, false);
        }

        return Html::tag('ul', implode("\n", $buttons), $this->options);
    }

    /**
     * @inheritdoc
     */
    protected function renderPageButton($label, $page, $class, $disabled, $active)
    {
        $options = ['class' => $class === '' ? null : $class];
        if ($active) {
            Html::addCssClass($options, $this->activePageCssClass);
        }
        if ($disabled) {
            Html::addCssClass($options, $this->disabledPageCssClass);

            return Html::tag('li', Html::tag('span', $label), $options);
        }
        $linkOptions = $this->linkOptions;
        $linkOptions['data-page'] = $page;

        // active page as anchor or span
        if ($active && !$this->activePageAsLink) {
            return Html::tag('li', Html::tag('span', $label, $linkOptions), $options);
        }

        return Html::tag('li', Html::a($label, $this->pagination->createUrl($page), $linkOptions), $options);
    }
}
