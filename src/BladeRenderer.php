<?php

namespace Spatie\MailTemplates;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\View\Factory;

class BladeRenderer
{

    /**
     * @var
     */
    protected $html;

    /**
     * @var Filesystem
     */
    protected $files;

    /**
     * @var Factory
     */
    protected $view;


    function __construct()
    {
        $this->files = app(Filesystem::class);
        $this->view = app('view');
    }

    /**
     * Render the view.
     *
     * @param string $html
     * @param array $data
     * @return \Illuminate\Contracts\View\View
     */
    public function render($html = '', $data = [])
    {
        $this->saveTemporaryHtml($html);

        $view = $this->view->file($this->getFilePath($html), $data);

        return tap($view->render(), function () use ($html) {
            $this->deleteTemporaryHtml($html);
        });
    }

    /**
     * Save the temporary file.
     * @param $html
     */
    protected function saveTemporaryHtml($html)
    {
        $this->files->put($this->getFileName($html), $html);
    }

    /**
     * Get the temp file name.
     *
     * @param $html
     * @return string
     */
    protected function getFileName($html)
    {
        return md5($html) . '.blade.php';
    }

    /**
     * Get the temp file path.
     *
     * @param $html
     * @return string
     */
    protected function getFilePath($html)
    {
        return storage_path('app/' . $this->getFileName($html));
    }

    /**
     * Delete the temporary file.
     *
     * @param $html
     */
    protected function deleteTemporaryHtml($html)
    {
        $this->files->delete($this->getFileName($html));
    }
}
