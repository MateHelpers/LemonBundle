<?php


namespace Mate\LemonBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class MateLemonGenerateController extends ContainerAwareCommand {

	protected function configure()
	{
		$this
			->setName('mate:lemon:generate:controller')
			->setDescription('Generate controller for given entity on bundle');
	}

	protected function execute( InputInterface $input, OutputInterface $output ) {
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
		$bundleQuestion = new Question('<info>Please enter the name of the bundle: </info>', 'AppBundle');

		$bundleQuestion->setValidator(function ($value) {
			if (trim($value) == '') {
				throw new \Exception('The bundle cannot be empty');
			}

			return $value;
		});


		$bundleQuestion->setAutocompleterValues($currentBundles);

		$bundle = $helper->ask($input, $output, $bundleQuestion);

		//ask for entity
		$entityQuestion = new Question('<info>Please enter the name of the entity: </info>');

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
			new ConfirmationQuestion('<question>Continue with this action? [y] </question>', true);

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

		$controllerQuestion =
			new ConfirmationQuestion('<question>Do you want to generate the controller? [y] </question>', true);

		if ($helper->ask($input, $output, $controllerQuestion)) {

			$io->section('Generating Controller class...');
			$lemonManager->generateController();
		}

		$io->success('MATE:LEMON:: CRUD generated successfully.');

		$io->table(
			array('Controller Class'),
			array(
				array(
					$lemonManager->getController()->getClassName()
				)
			)
		);

	}

}