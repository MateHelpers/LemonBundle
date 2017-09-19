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

namespace Mate\LemonBundle\Command;

use Mate\LemonBundle\Element\EntityProperty;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class MateLemonGenerateFullCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('mate:lemon:generate:full')
            ->setDescription('Generate CRUD with views for given entity on bundle');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');
        $lemonManager = $this->getContainer()->get('mate_lemon.manager');

        $bundlesObjects = $this->getContainer()->get('kernel')->getBundles();
        $currentBundles = [];

        /** @var BundleInterface $bundlesObject */
        foreach ($bundlesObjects as $bundlesObject) {
            $currentBundles[] = $bundlesObject->getName();
        }

        //ask for bundle
        $bundleQuestion = new Question("<info>Please enter the name of the bundle: </info>", 'AppBundle');

        $bundleQuestion->setValidator(function ($value) {
            if (trim($value) == '') {
                throw new \Exception('The bundle cannot be empty');
            }

            return $value;
        });


        $bundleQuestion->setAutocompleterValues($currentBundles);

        $bundle = $helper->ask($input, $output, $bundleQuestion);

        //ask for entity
        $entityQuestion = new Question("<info>Please enter the name of the entity: </info>");

        //validate the entity input
        $entityQuestion->setValidator(function ($value) {
            if (trim($value) == '') {
                throw new \Exception('The entity cannot be empty');
            }

            return $value;
        });

        $entity = $helper->ask($input, $output, $entityQuestion);

        //Confirm action
        $confirmQuestion =
            new ConfirmationQuestion("<question>Continue with this action? [y] </question>", true);

        if (!$helper->ask($input, $output, $confirmQuestion)) {
            return;
        }

	    //ask for entity
	    $controllerFolderQuestion = new Question("<info>Please enter the name of controllers folder: </info>");

	    //validate the entity input
	    $controllerFolderQuestion->setValidator(function ($value) {
		    if (trim($value) == '') {
			    return null;
		    }

		    return $value;
	    });

	    $controllerFolder = $helper->ask($input, $output, $controllerFolderQuestion);

        $lemonManager->load($bundle, $entity, compact('controllerFolder'));

        $entityElement = $lemonManager->getEntity();

        /** @var EntityProperty $property */
        foreach ($entityElement->getProperties() as $property) {
            $propertyName = $property->getName();

            $addAsDesiredQuestion = new ConfirmationQuestion(
                "<info>Do you want to display the property $propertyName? [y] </>",
                true
            );

            if ($helper->ask($input, $output, $addAsDesiredQuestion)) {
                $property->setAsDesired();
            }
        }

        //reload the form
        $lemonManager->reloadForm();

        //Confirm action
        $generateMenuQuestion =
            new ConfirmationQuestion("<info>Do you want to generate the menu? [y] </info>", true);

        if ($helper->ask($input, $output, $generateMenuQuestion)) {
            //extract menu
            $menu = $lemonManager->getMenu();


            if (!file_exists($menu->getPath())) {
                $io->section('Generating new menu view file...');
                $lemonManager->generateMenu();
                $lemonManager->generateMenuPartial();
            } else {
                $io->section('Updating menu view file...');
                $lemonManager->generateMenuPartial();
            }
        }

        $header = $lemonManager->getHeader();

        if (!file_exists($header->getPath())) {
            $generateHeaderQuestion =
                new ConfirmationQuestion("<info>Do you want to generate the header? [y] </info>", true);
            if ($helper->ask($input, $output, $generateHeaderQuestion)) {
                $io->section('Generating header view file...');
                $lemonManager->generateHeader();
            }
        }

        $layout = $lemonManager->getLayout();

        if (!file_exists($layout->getPath())) {
            $generateLayoutQuestion =
                new ConfirmationQuestion("<info>Do you want to generate the layout? [y] </info>", true);
            if ($helper->ask($input, $output, $generateLayoutQuestion)) {
                $io->section('Generating layout view file...');
                $lemonManager->generateLayout();
            }
        }


	    $formQuestion =
		    new ConfirmationQuestion("<question>Do you want to generate the form? [y] </question>", true);

	    if ($helper->ask($input, $output, $formQuestion)) {
		    $io->section('Generating Form class...');
		    $lemonManager->generateForm();
	    }

	    $controllerQuestion =
		    new ConfirmationQuestion("<question>Do you want to generate the controller? [y] </question>", true);

	    if ($helper->ask($input, $output, $controllerQuestion)) {
		    $io->section('Generating Controller class...');
		    $lemonManager->generateController();
	    }


	    $viewsQuestion =
		    new ConfirmationQuestion("<question>Do you want to generate views? [y] </question>", true);

	    if ($helper->ask($input, $output, $viewsQuestion)) {
		    $io->section('Generating views files...');
		    $lemonManager->generateViews();
	    }

        $io->success('MATE:LEMON:: CRUD generated successfully.');

        $io->table(
            array('Form Class', 'Controller Class'),
            array(
                array(
                    $lemonManager->getForm()->getClassName(),
                    $lemonManager->getController()->getClassName()
                )
            )
        );

    }

}
