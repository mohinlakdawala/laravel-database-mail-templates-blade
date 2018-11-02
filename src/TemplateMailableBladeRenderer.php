<?php

namespace Spatie\MailTemplates;


class TemplateMailableBladeRenderer
{
    /** @var \Spatie\MailTemplates\TemplateMailable */
    protected $templateMailable;

    /** @var \Spatie\MailTemplates\Models\MailTemplate */
    protected $mailTemplate;

    /** @var \Spatie\MailTemplates\BladeRenderer */
    protected $blade;

    public function __construct(TemplateMailable $templateMailable, BladeRenderer $blade)
    {
        $this->templateMailable = $templateMailable;
        $this->blade = $blade;

        $templateModel = $this->templateMailable->getTemplateModel();
        $this->mailTemplate = $templateModel::findForMailable($templateMailable);
    }

    public function render(array $data = []): string
    {
        return $this->blade->render(
            $this->mailTemplate->template,
            $data
        );
    }

    public function renderSubject(array $data = []): string
    {
        return $this->blade->render(
            $this->mailTemplate->subject,
            $data
        );
    }
}
