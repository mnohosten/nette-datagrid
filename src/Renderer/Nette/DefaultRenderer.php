<?php
declare(strict_types=1);

namespace Mnohosten\DataGrid\Renderer\Nette;

use Mnohosten\DataGrid\DataGrid;
use Mnohosten\DataGrid\Filter\DateFilter;
use Mnohosten\DataGrid\Filter\SelectFilter;
use Mnohosten\DataGrid\Filter\TextFilter;
use Mnohosten\DataGrid\Renderer\Nette\Factory\FormFactory;
use Nette\Application\UI\Control;
use Nette\Application\UI\Form;
use Nette\Http\SessionSection;
use Tracy\Debugger;

class DefaultRenderer extends Control
{
    /** @persistent */
    public int $page = 1;
    /** @persistent */
    public int $itemsPerPage = 50;
    public DataGrid $dataGrid;
    protected string $templateFile = __DIR__ . '/templates/default.latte';
    private string $filterCaption;
    private array $itemsPerPageList = [
        10, 20, 50, 100, 500
    ];

    public function __construct(
        DataGrid $dataGrid,
        string $filterCaption = 'Hledání v obsahu'
    )
    {
        $this->dataGrid = $dataGrid;
        $this->filterCaption = $filterCaption;
    }


    public function render(): void
    {
        $this->dataGrid->getDataSource()->filter(
            $this->dataGrid->getFilters(),
            $this->getParameters(),
        );

        $itemsPerPage = $this->getItemsPerPage();
        $this->dataGrid->getDataSource()->reduce(
            $itemsPerPage + 1,
            $this->page == 1 ? 0 : ($this->page - 1) * $itemsPerPage
        );
        $data = $this->dataGrid->getDataSource()->getData();
        if(!is_array($data)) $data = iterator_to_array($data);

        $nextLink = count($data) > $itemsPerPage
            ? $this->link('this', ['page' => $this->page + 1] + $this->getParameters())
            : '';
        $prevLink = $this->page > 1
            ? $this->link('this', ['page' => $this->page - 1] + $this->getParameters())
            : '';
        $showPagination = $nextLink || $prevLink;
        if($nextLink) {
            array_pop($data);
        }

        $this->template->render(
            $this->templateFile,
            [
                'dataGrid' => $this->dataGrid,
                'filterCaption' => $this->filterCaption,
                'page' => $this->page,
                'data' => $data,
                'showPagination' => $showPagination,
                'nextLink' => $nextLink,
                'prevLink' => $prevLink,
                'expandFilterForm' => $this->expandFilterForm(),
                'showActions' => !empty($this->dataGrid->getActions()),
                'actions' => $this->dataGrid->getActions(),
            ]
        );
    }

    private function expandFilterForm(): bool
    {
        foreach ($this['filterForm']->getValues(true) as $value) {
            if($value) return true;
        }
        return false;
    }

    public function setTemplateFile(string $templateFile): void
    {
        $this->templateFile = $templateFile;
    }

    public function createComponentFilterForm(): Form
    {
        $form = (new FormFactory())->create();
        $form->setMethod('GET');
        $this->addComponent($form, 'filterForm');
        foreach ($this->dataGrid->getFilters() as $filter) {
            if ($filter instanceof DateFilter) {
                $form->addText($filter->getName(), $filter->getLabel())
                    ->getControlPrototype()->addAttributes([
                        'class' => 'datetimepicker'
                    ]);
            }
            if ($filter instanceof TextFilter) {
                $form->addText($filter->getName(), $filter->getLabel());
            }
            if ($filter instanceof SelectFilter) {
                $form->addSelect($filter->getName(), $filter->getLabel(), $filter->getValues())
                    ->setPrompt('---');
            }
        }
        $form->addSubmit('send', 'Filtrovat');
        $form->addSubmit('reset', 'Obnovit')
            ->getControlPrototype()
            ->addAttributes(['class' => 'btn-danger']);
        $form->onSubmit[] = function(Form $form) {
            $this->redirect('this', ['page' => 1] + $form->getValues(true));
        };
        if ($form['reset']->isSubmittedBy()) {
            $this->redirect('this', ['page' => 1]);
        }

        foreach ($this->getParameters() as $key=>$value) {
            if(isset($form[$key])) {
                $form[$key]->setDefaultValue($value);
            }
        }

        return $form;
    }

    public function createComponentPerPageListForm(): Form
    {
        $form = (new FormFactory())->create();
        $this->addComponent($form, 'perPageListForm');
        $form->setMethod('GET');
        $form->setAction($this->link('this', $this->getParameters()));
        $form->addSelect(
            'itemsPerPage',
            'Záznamů na stránku',
            array_combine($this->itemsPerPageList, $this->itemsPerPageList)
        );
        $form->addSubmit('send', 'Zobrazit');
        $form['itemsPerPage']->setDefaultValue($this->getItemsPerPage());

        $form->onSuccess[] = function(Form $form) {
            $this->redirect(
                'this',
                [
                    'page' => 1,
                    'itemsPerPage' => $form->getValues()->itemsPerPage
                ]
                + $this->getParameters()
            );
        };
        return $form;
    }

    public function getItemsPerPage(): int
    {
        return $this->getParameter('itemsPerPage', $this->itemsPerPage);
    }

    /**
     * @param array|int[] $itemsPerPageList
     */
    public function setItemsPerPageList(array $itemsPerPageList): void
    {
        $this->itemsPerPageList = $itemsPerPageList;
    }
}
