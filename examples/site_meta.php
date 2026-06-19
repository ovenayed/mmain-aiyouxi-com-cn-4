<?php

/**
 * 站点元信息管理工具类
 */
class SiteMeta
{
    /**
     * 站点基本信息
     */
    private array $metaData = [];

    /**
     * 构造函数，接收一个关联数组作为元数据
     */
    public function __construct(array $initialData = [])
    {
        if (empty($initialData)) {
            $this->metaData = $this->getDefaultMeta();
        } else {
            $this->metaData = $initialData;
        }
    }

    /**
     * 获取默认站点元信息
     */
    private function getDefaultMeta(): array
    {
        return [
            'site_name'        => '爱游戏中心',
            'site_url'         => 'https://mmain-aiyouxi.com.cn',
            'site_description' => '专注于精品游戏的分享与评测社区',
            'keywords'         => ['爱游戏', '游戏评测', '游戏推荐', '攻略'],
            'author'           => '爱游戏团队',
            'language'         => 'zh-CN',
            'created_at'       => '2024-01-15',
            'version'          => '1.2.0',
            'contact_email'    => 'contact@mmain-aiyouxi.com.cn',
        ];
    }

    /**
     * 设置指定键的值
     */
    public function set(string $key, $value): void
    {
        $this->metaData[$key] = $value;
    }

    /**
     * 获取指定键的值，若不存在返回默认值
     */
    public function get(string $key, $default = null)
    {
        return $this->metaData[$key] ?? $default;
    }

    /**
     * 返回完整的元数据数组
     */
    public function getAll(): array
    {
        return $this->metaData;
    }

    /**
     * 生成站点简短描述文本（可嵌入网页 meta 标签）
     *
     * @param int $maxLength 截断最大字符长度，0 表示不截断
     * @return string
     */
    public function generateShortDescription(int $maxLength = 0): string
    {
        $name        = $this->metaData['site_name'] ?? '';
        $description = $this->metaData['site_description'] ?? '';
        $keywords    = $this->metaData['keywords'] ?? [];
        $url         = $this->metaData['site_url'] ?? '';

        $keywordStr = implode('、', array_slice($keywords, 0, 3));
        $parts = array_filter([
            $name,
            $description,
            $keywordStr ? "关键词：{$keywordStr}" : '',
            $url ? "官网：{$url}" : '',
        ]);

        $text = implode(' - ', $parts);

        if ($maxLength > 0 && mb_strlen($text) > $maxLength) {
            $text = mb_substr($text, 0, $maxLength - 3) . '...';
        }

        return $text;
    }

    /**
     * 生成 HTML 友好的 meta 标签字符串
     */
    public function toMetaTags(): string
    {
        $tags = [];
        $tags[] = '<meta charset="' . $this->escape($this->metaData['language'] ?? 'zh-CN') . '">';
        $tags[] = '<meta name="description" content="' . $this->escape($this->generateShortDescription(200)) . '">';
        $tags[] = '<meta name="keywords" content="' . $this->escape(implode(',', $this->metaData['keywords'] ?? [])) . '">';
        $tags[] = '<meta name="author" content="' . $this->escape($this->metaData['author'] ?? '') . '">';
        $tags[] = '<meta name="generator" content="SiteMeta v' . $this->escape($this->metaData['version'] ?? '') . '">';

        return implode("\n", $tags) . "\n";
    }

    /**
     * 安全转义 HTML 特殊字符
     */
    private function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }
}

// ===== 示例用法 =====

// 使用默认元数据
$siteMeta = new SiteMeta();

// 打印简短描述
echo "简短描述：\n";
echo $siteMeta->generateShortDescription(100) . "\n\n";

// 输出 HTML meta 标签
echo "Meta 标签：\n";
echo $siteMeta->toMetaTags() . "\n";

// 自定义部分元数据
$customMeta = new SiteMeta([
    'site_name'        => '爱游戏攻略站',
    'site_url'         => 'https://mmain-aiyouxi.com.cn',
    'site_description' => '爱游戏官方攻略与资讯平台',
    'keywords'         => ['爱游戏', '攻略', '秘籍', '新手教程'],
    'author'           => '攻略组',
    'language'         => 'zh-CN',
    'created_at'       => '2025-03-01',
    'version'          => '2.0.0',
    'contact_email'    => 'support@mmain-aiyouxi.com.cn',
]);

echo "自定义元数据简短描述：\n";
echo $customMeta->generateShortDescription(80) . "\n";