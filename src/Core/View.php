<?php

declare(strict_types=1);

namespace App\Core;

use Smarty;

final class View
{
    private Smarty $smarty;

    public function __construct(string $templatePath)
    {
        $this->smarty = new Smarty();
        $this->smarty->setTemplateDir($templatePath);
        $this->smarty->setCompileDir(sys_get_temp_dir() . '/smarty_compile');
        $this->smarty->setCacheDir(sys_get_temp_dir() . '/smarty_cache');
        $this->smarty->escape_html = true;
    }

    /**
     * @param array<string, mixed> $data
     */
    public function render(string $template, array $data = []): string
    {
        foreach ($data as $key => $value) {
            $this->smarty->assign($key, $value);
        }

        return $this->smarty->fetch($template);
    }
}
