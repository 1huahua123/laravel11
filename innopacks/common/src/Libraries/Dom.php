<?php
/**
 * @Author     : Ray
 * @Date       : 2025/2/15 16:08
 * @Project    : laravel11
 * @Description: 操作HTML文档
 */

namespace InnoShop\Common\Libraries;

use DOMDocument;
use DOMNodeList;
use DOMXPath;

class Dom
{
    // 私有属性，用于存储DOMDocument实例
    private DOMDocument $document;

    // 私有属性，用于存储DOMXPath实例
    private DOMXPath $xpath;

    /**

     * 构造函数
     * @param string $htmlContent HTML内容
     */
    public function __construct(string $htmlContent)
    {
        // 创建DOMDocument实例
        $this->document = new DOMDocument();
        // 加载HTML内容，忽略解析错误
        @$this->document->loadHTML($htmlContent, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
        // 创建DOMXPath实例，用于查询DOM文档
        $this->xpath = new DOMXPath($this->document);
    }

    /**

     * 静态方法，用于获取Dom类的实例
     * @param string $htmlContent HTML内容
     * @return self Dom类的实例
     */
    public static function getInstance(string $htmlContent): Dom
    {
        // 返回新的Dom实例
        return new self($htmlContent);
    }

    /**

     * 在指定选择器之前插入内容
     * @param string $selector CSS选择器
     * @param string $content 要插入的内容
     * @return false|string 失败返回false，成功返回HTML内容
     */
    public function insertBefore(string $selector, string $content): false|string
    {
        // 查找匹配的节点
        $nodes = $this->findNodes($selector);
        // 遍历节点
        foreach ($nodes as $node) {
            // 创建文档片段
            $fragment = $this->document->createDocumentFragment();
            // 将内容附加到文档片段
            $fragment->appendXML($content);
            // 在节点之前插入文档片段
            $node->parentNode->insertBefore($fragment, $node);
        }

        // 返回修改后的HTML内容
        return $this->document->saveHTML();
    }

    /**

     * 在指定选择器之后插入内容
     * @param string $selector CSS选择器
     * @param string $content 要插入的内容
     * @return false|string 失败返回false，成功返回HTML内容
     */
    public function insertAfter(string $selector, string $content): false|string
    {
        // 查找匹配的节点
        $nodes = $this->findNodes($selector);
        // 遍历节点
        foreach ($nodes as $node) {
            // 创建文档片段
            $fragment = $this->document->createDocumentFragment();
            // 将内容附加到文档片段
            $fragment->appendXML($content);
            // 如果节点有下一个兄弟节点，则在其之前插入文档片段
            if ($node->nextSibling) {
                $node->parentNode->insertBefore($fragment, $node->nextSibling);
            } else {
                // 否则将文档片段追加到父节点的末尾
                $node->parentNode->appendChild($fragment);
            }
        }

        // 返回修改后的HTML内容
        return $this->document->saveHTML();
    }

    /**

     * 替换指定选择器的内容
     * @param string $selector CSS选择器
     * @param string $content 要替换的内容
     * @return false|string 失败返回false，成功返回HTML内容
     */
    public function replaceContent(string $selector, string $content): false|string
    {
        // 查找匹配的节点
        $nodes = $this->findNodes($selector);
        // 遍历节点
        foreach ($nodes as $node) {
            // 创建文档片段
            $fragment = $this->document->createDocumentFragment();
            // 将内容附加到文档片段
            $fragment->appendXML($content);
            // 替换节点
            $node->parentNode->replaceChild($fragment, $node);
        }

        // 返回修改后的HTML内容
        return $this->document->saveHTML();
    }

    /**

     * 查找匹配选择器的节点
     * @param string $selector CSS选择器
     * @return DOMNodeList|false|mixed|null 节点列表或false或null
     */
    private function findNodes(string $selector): mixed
    {
        // 如果选择器以'.'开头，表示类选择器
        if (str_starts_with($selector, '.')) {
            // 获取类名
            $className = substr($selector, 1);

            // 返回匹配类名的节点列表
            return $this->xpath->query("//*[contains(concat(' ', normalize-space(@class), ' '), ' $className ')]");
        // 如果选择器以'#'开头，表示ID选择器
        } elseif (str_starts_with($selector, '#')) {
            // 获取ID名
            $idName = substr($selector, 1);

            // 返回匹配ID的节点列表
            return $this->xpath->query("//*[@id='$idName']");
        }

        // 如果选择器不符合类或ID选择器，返回null
        return null;
    }
}
