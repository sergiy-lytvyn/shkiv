<?php

namespace App\Http\Sections;

use AdminColumn;
use AdminColumnFilter;
use AdminDisplay;
use AdminForm;
use AdminFormElement;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NestedSet;
use SleepingOwl\Admin\Contracts\Display\DisplayInterface;
use SleepingOwl\Admin\Contracts\Form\FormInterface;
use SleepingOwl\Admin\Contracts\Initializable;
use SleepingOwl\Admin\Form\Buttons\Cancel;
use SleepingOwl\Admin\Form\Buttons\Save;
use SleepingOwl\Admin\Form\Buttons\SaveAndClose;
use SleepingOwl\Admin\Form\Buttons\SaveAndCreate;
use SleepingOwl\Admin\Section;
use SleepingOwl\Admin\Display\Tree\OrderTreeType;
use App\Entities\Category;
use App\Custom\Select;
/**
 * Class Categories
 *
 * @property \App\User $model
 *
 * 
 */
class Categories extends Section implements Initializable
{
    /**
     * @var bool
     */
    protected $checkAccess = false;

    /**
     * @var string
     */
    protected $title = "Категории";

    /**
     * @var string
     */
    protected $alias;

    /**
     * Initialize class.
     */
    public function initialize()
    {
        $this->addToNavigation()->setPriority(1)->setIcon('fas fa-sitemap');
    }

    /**
     * @param array $payload
     *
     * @return DisplayInterface
     */
    public function onDisplay($payload = [])
    {
        return AdminDisplay::tree()->setValue('name_ru')
            ->setNewEntryButtonText('Добавить');
    }

    protected function getCategory()
    {
        $categories = \App\Entities\Category::where('status', 1)->get();
        $arr = [];
        foreach ($categories as $category){
            if($category->parent_id == 0) {
                $arr[$category->id] = $category->name_ru;
            }

            if(count($category->children) > 0) {
                foreach ($category->children as $item) {
                    $arr[$item->id] = $category->name_ru . ' - ' . $item->name_ru;
                }
            }
        }

        return $arr;
    }

    /**
     * @param int|null $id
     * @param array $payload
     *
     * @return FormInterface
     */
    public function onEdit($id = null, $payload = [])
    {
        $tabs = AdminDisplay::tabbed();
        $tabs->setTabs(function ($id) {
            $tabs = [];

            $tabs[] = AdminDisplay::tab(AdminForm::elements([

                    AdminFormElement::columns()->addColumn([
                        AdminFormElement::text('name_uk', 'Назва UA')
                            ->required(),
                        AdminFormElement::text('name_ru', 'Названия RU')
                            ->required(),
                        AdminFormElement::text('name_en', 'Названия En')
                            ->required(),
                        AdminFormElement::text('name_pl', 'Названия PL')
                            ->required(),
                        AdminFormElement::text('slug_uk', 'Alias UA')
                            ->unique()
                            ->required(),
                        AdminFormElement::text('slug_ru', 'Alias RU')
                            ->unique()
                            ->required(),
                        AdminFormElement::text('slug_en', 'Alias EN')
                            ->unique()
                            ->required(),
                        AdminFormElement::text('slug_pl', 'Alias PL')
                            ->unique()
                            ->required(),
                        AdminFormElement::select('parent_id')->setLabel('Родительская категория')
                            //->setModelForOptions(\App\Entities\Category::class)
                            ->setOptions($this->getCategory())
                            ->setHtmlAttribute('placeholder', 'Выбрать')
                            ->setDisplay('name_ru'),
                        AdminFormElement::ckeditor('content_uk', 'Описание UA'),
                        AdminFormElement::ckeditor('content_ru', 'Описание RU'),
                        AdminFormElement::ckeditor('content_en', 'Описание EN'),
                        AdminFormElement::ckeditor('content_pl', 'Описание PL'),

                        AdminFormElement::image('image', 'Изображения')->setUploadPath(function (\Illuminate\Http\UploadedFile $file) {
                            return 'files/category';
                        }),
                        AdminFormElement::image('image_catalog', 'Банер в каталоге')->setUploadPath(function (\Illuminate\Http\UploadedFile $file) {
                            return 'files/category';
                        }),
                        AdminFormElement::text('css_style', 'ID'),

                        AdminFormElement::radio('status', 'Побликация')
                            ->setOptions(['0' => 'Не опубликовано', '1' => 'Опубликовано'])
                            ->setDefaultValue(1)
                            ->required(),
                    ], 8),

            ]))->setLabel('Основная информация');

            $tabs[] = AdminDisplay::tab(new \SleepingOwl\Admin\Form\FormElements([
                AdminFormElement::text('seo_title_uk', 'Title UA'),
                AdminFormElement::text('seo_title_ru', 'Title RU'),
                AdminFormElement::text('seo_title_en', 'Title EN'),
                AdminFormElement::text('seo_title_pl', 'Title PL'),
                AdminFormElement::textarea('description_uk', 'Описание UA')->setRows(3),
                AdminFormElement::textarea('description_ru', 'Описание RU')->setRows(3),
                AdminFormElement::textarea('description_en', 'Описание EN')->setRows(3),
                AdminFormElement::textarea('description_pl', 'Описание PL')->setRows(3),
            ]))->setLabel('SEO');

            return $tabs;
        });

        $form = AdminForm::form()->addElement($tabs);

        $form->getButtons()->setButtons([
            'save'  => new Save(),
            'save_and_close'  => new SaveAndClose(),
            'save_and_create'  => new SaveAndCreate(),
            'cancel'  => (new Cancel()),
        ]);

        return $form;
    }

    /**
     * @return FormInterface
     */
    public function onCreate($payload = [])
    {
        return $this->onEdit(null, $payload);
    }

    /**
     * @return bool
     */
    public function isDeletable(Model $model)
    {
        return true;
    }

    /**
     * @return void
     */
    public function onRestore($id)
    {
        // remove if unused
    }
}

