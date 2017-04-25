<?php
/**
 * This file is part of the MateLemonBundle package.
 *
 * (c) Mohamed Radhi Guennichi <https://www.mate.tn> <https://github.com/MateHelpers/LemonBundle>
 *
 * Email: rg@mate.tn - contact@mate.tn
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mate\LemonBundle\Service;


use Doctrine\ORM\EntityManager;
use Mate\LemonBundle\Element\Controller;
use Mate\LemonBundle\Element\ControllerMethod;
use Mate\LemonBundle\Element\Entity;
use Mate\LemonBundle\Element\EntityProperty;
use Mate\LemonBundle\Element\Form;
use Mate\LemonBundle\Element\FormProperty;
use Mate\LemonBundle\Element\Header;
use Mate\LemonBundle\Element\Layout;
use Mate\LemonBundle\Element\Menu;
use Mate\LemonBundle\Element\MenuPartial;
use Mate\LemonBundle\Element\View;
use Mate\LemonBundle\Generator\ControllerGenerator;
use Mate\LemonBundle\Generator\FormGenerator;
use Mate\LemonBundle\Generator\GeneratorAbstraction;
use Mate\LemonBundle\Generator\HeaderGenerator;
use Mate\LemonBundle\Generator\LayoutGenerator;
use Mate\LemonBundle\Generator\MenuGenerator;
use Mate\LemonBundle\Generator\MenuPartialGenerator;
use Mate\LemonBundle\Generator\ViewGenerator;
use Mate\LemonBundle\Helper\TemplateHelper;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class LemonService
{
    /** @var EntityManager */
    protected $manager;

    /** @var KernelInterface */
    protected $kernel;

    /** @var Finder */
    protected $finder;

    /** @var Filesystem */
    protected $filesystem;

    /** @var BundleInterface */
    protected $bundle;

    /** @var Entity */
    protected $entity;

    /** @var Form */
    protected $form;

    /** @var Controller */
    protected $controller;

    /** @var array */
    protected $views;

    /** @var Menu */
    protected $menu;

    /** @var MenuPartial */
    protected $menuPartial;

    /** @var Header */
    protected $header;

    /** @var Layout */
    protected $layout;

    /** @var string */
    private $viewsDirectory;

    /** @var boolean|null */
    private $overrideTemplate = false;

    protected $isLoaded = false;

    public function __construct(EntityManager $manager, KernelInterface $kernel, Filesystem $filesystem, $overrideTemplate = false)
    {
        $this->manager = $manager;
        $this->kernel = $kernel;
        $this->filesystem = $filesystem;
        $this->finder = new Finder();
        $this->overrideTemplate = $overrideTemplate;
    }

    public function load($bundleName, $entityName)
    {
        $this->isLoaded       = true;

        //firstly load the bundle
        $this->bundle         = $this->loadBundle($bundleName);
        $this->viewsDirectory = $this->loadViewsDirectory();

        $this->entity         = $this->loadEntity($entityName);
        $this->form           = $this->loadForm();
        $this->controller     = $this->loadController();
        $this->views          = $this->loadViews();
        $this->menu           = $this->loadMenu();
        $this->menuPartial    = $this->loadMenuPartial();
        $this->header         = $this->loadHeader();
        $this->layout         = $this->loadLayout();

        return $this;
    }

    public function generateLayout()
    {
        if (!$this->isLoaded)
            throw new RuntimeException('Service not loaded yet!');

        $this->generate(new LayoutGenerator($this->layout));
    }

    public function generateHeader()
    {
        if (!$this->isLoaded)
            throw new RuntimeException('Service not loaded yet!');

        $this->generate(new HeaderGenerator($this->header));
    }

    public function generateMenuPartial()
    {
        if (!$this->isLoaded)
            throw new RuntimeException('Service not loaded yet!');

        $this->generate(new MenuPartialGenerator($this->menuPartial));
    }

    public function generateMenu()
    {
        if (!$this->isLoaded)
            throw new RuntimeException('Service not loaded yet!');

        $this->generate(new MenuGenerator($this->menu));
    }

    public function generateViews()
    {
        if (!$this->isLoaded)
            throw new RuntimeException('Service not loaded yet!');

        /** @var View $view */
        foreach ($this->views as $view) {
            $this->generate(new ViewGenerator($view));
        }
    }

    public function generateController()
    {
        if (!$this->isLoaded)
            throw new RuntimeException('Service not loaded yet!');

        $this->generate(new ControllerGenerator($this->controller));
    }

    public function generateForm()
    {
        if (!$this->isLoaded)
            throw new RuntimeException('Service not loaded yet!');

        $this->generate(new FormGenerator($this->form));
    }

    /**
     * @param GeneratorAbstraction $generator
     */
    private function generate(GeneratorAbstraction $generator)
    {
        $generator->build($this->filesystem);
    }

    /**
     * @return Controller
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @return Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @return Entity
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @return BundleInterface
     */
    public function getBundle()
    {
        return $this->bundle;
    }

    /**
     * @return Menu
     */
    public function getMenu()
    {
        return $this->menu;
    }

    /**
     * @return MenuPartial
     */
    public function getMenuPartial()
    {
        return $this->menuPartial;
    }

    /**
     * @return Header
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * @return Layout
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * @return string
     */
    public function getViewsDirectory()
    {
        return $this->viewsDirectory;
    }

    public function reloadForm()
    {
        $this->form = $this->loadForm();
    }

    /**
     * depends on: ($this->controller)
     * priority: 5
     * @return array
     */
    private function loadViews()
    {
        $controller = $this->controller;

        $methods = $controller->getMethods();
        $views = [];

        $counter = 0;

        /** @var ControllerMethod $method */
        foreach ($methods as $method) {
            if ($method->hasView()) {
                if ($method->hasForm()) {
                    $counter++;

                    $viewForm = new View($method, $method->getLowerName(), $this->viewsDirectory, $this->loadTemplateContent(TemplateHelper::VIEW_FORM));
                    $method->setView($viewForm);

                    if ($counter > 1) {
                        continue;
                    }

                    $views[] = $viewForm;
                } else {
                    $templateRef = new \ReflectionClass(TemplateHelper::class);
                    $simpleView = new View(
                        $method,
                        $method->getLowerName(),
                        $this->viewsDirectory,
                        $this->loadTemplateContent($templateRef->getConstant('VIEW_' . strtoupper($method->getName()))));

                    $method->setView($simpleView);

                    $views[] = $simpleView;
                }
            }
        }

        return $views;
    }

    /**
     * depends on: ($this->bundle, $this->form)
     * priority: 4
     * @return Controller
     */
    private function loadController()
    {
        $controller = new Controller($this->bundle, $this->form, $this->loadTemplateContent(TemplateHelper::CONTROLLER));

        /** @var $methodsArray : Methods names as keys and isRouteContainsId as values */
        $methodsArray = [
            'Index' => [
                'isRouteContainsId' => false,
                'hasForm' => false,
                'hasView' => true
            ],
            'Create' => [
                'isRouteContainsId' => false,
                'hasForm' => true,
                'hasView' => true
            ],
            'Update' => [
                'isRouteContainsId' => true,
                'hasForm' => true,
                'hasView' => true
            ],
            'Show' => [
                'isRouteContainsId' => true,
                'hasForm' => false,
                'hasView' => true
            ],
            'Delete' => [
                'isRouteContainsId' => true,
                'hasForm' => false,
                'hasView' => false
            ],
        ];

        foreach ($methodsArray as $name => $options) {
            $controller->addMethod($name, $options['isRouteContainsId'], $options['hasForm'], $options['hasView']);
        }

        return $controller;
    }

    /**
     * depends on: ($this->bundle, $this->entity)
     * priority: 4
     * @return Form
     */
    private function loadForm()
    {
        $form = new Form($this->entity, $this->bundle, $this->loadTemplateContent(TemplateHelper::FORM));

        /** @var EntityProperty $property */
        foreach ($this->entity->getDesiredProperties() as $property) {
            $form->addProperty(new FormProperty($property));
        }

        return $form;
    }

    /**
     * This method has as property dependencies $this->bundle
     * So this method should be called after loading the bundle
     * depends on: ($this->bundle)
     * priority: 3
     *
     * @param $entityName
     * @return Entity
     */
    private function loadEntity($entityName)
    {
        //check entity exists
        try {
            $classMetadata = $this->manager->getClassMetadata($this->bundle->getName() . ':' . $entityName);
        } catch (\Exception $exception) {
            throw new RuntimeException($exception);
        }

        //save data entity
        $entity = new Entity($classMetadata);
        //add properties
        foreach ($fieldMappings = $entity->getFieldMappings() as $fieldMapping) {
            $entity->addProperty(new EntityProperty($fieldMapping));
        }

        return $entity;
    }

    /**
     * depends on: $this->bundle
     * priority: 2
     * @return string
     */
    private function loadViewsDirectory()
    {
        if ($this->bundle->getName() == 'AppBundle') {
            $absolutePath = $this->kernel->getRootDir() . '/..';
            return $absolutePath . '/app/Resources/views/LemonGenerator';
        }

        return $this->bundle->getPath() . '/Resources/views/LemonGenerator';
    }

    /**
     * priority: 6
     * @return Menu
     */
    private function loadMenu()
    {
        $menu = new Menu($this->controller, $this->viewsDirectory, $this->loadTemplateContent(TemplateHelper::MENU));

        return $menu;
    }

    /**
     * priority: 7
     * @return MenuPartial
     */
    private function loadMenuPartial()
    {
        $menuPartial = new MenuPartial($this->controller, $this->viewsDirectory, $this->loadTemplateContent(TemplateHelper::MENU_PARTIAL));

        return $menuPartial;
    }

    /**
     * priority: 8/3
     * @return Header
     */
    private function loadHeader()
    {
        $header = new Header($this->viewsDirectory, $this->loadTemplateContent(TemplateHelper::HEADER));

        return $header;
    }

    /**
     * priority: 8/3
     * @return Layout
     */
    private function loadLayout()
    {
        $layout = new Layout($this->viewsDirectory, $this->loadTemplateContent(TemplateHelper::LAYOUT));

        return $layout;
    }

    /**
     * depends on: null
     * priority: 1
     * @param $bundleName
     * @return BundleInterface|BundleInterface[]
     */
    private function loadBundle($bundleName)
    {
        return $this->kernel->getBundle($bundleName);
    }

    /**
     * Load the template content for given element (form, controller or view)
     * @param $name
     * @return null|string
     */
    private function loadTemplateContent($name)
    {
        $templateOriginalPath = $this->kernel->locateResource('@MateLemonBundle/' . TemplateHelper::TEMPLATE_FOLDER_NAME);

        if ($this->overrideTemplate)
            $templatePath = $this->kernel->locateResource('@' . $this->bundle->getName() . '/' . TemplateHelper::TEMPLATE_FOLDER_NAME);
        else
            $templatePath = $templateOriginalPath;

        $templates = $this->finder->in($templatePath)->files()->name($name);

        if (!$templates->count()) {
            //find in the default template folder
            $templates = $this->finder->in($templateOriginalPath)->files()->name($name);

            if (!$templates->count()) {
                throw new \RuntimeException('Template file not found');
            }
        }

        $templateContent = null;

        /** @var SplFileInfo $template */
        foreach ($templates as $template) {
            if ($template->getFilename() == $name) {
                $templateContent = $template->getContents();
                break;
            }
        }

        return $templateContent;
    }

    public function copyTemplateFolder()
    {
        $this->filesystem->copy(
            $this->kernel->locateResource('@MateLemonBundle/' . TemplateHelper::TEMPLATE_FOLDER_NAME),
            $this->bundle->getPath()
        );
    }
}