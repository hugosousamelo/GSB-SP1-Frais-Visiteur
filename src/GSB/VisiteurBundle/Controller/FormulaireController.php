<?php

namespace GSB\VisiteurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use GSB\VisiteurBundle\Entity\Visiteur;
use Doctrine\ORM\VisiteurRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use GSB\VisiteurBundle\Form\VisiteurType;

class FormulaireController extends Controller{

	public function formAction(Request $query){

		$visiteur = new Visiteur();// creer un objet Visiteur
		$form = $this->createForm(VisiteurType::class, $visiteur);

		if ($query->isMethod('POST')) {
			$form->handleRequest($query);

			if ($form->isValid()) {	// verifier si les valeur sont correcte
				$em = $this->getDoctrine()->getManager();
				$em->persist($visiteur);
				$em->flush();
				$query->getSession()->getFlashBag()->add('notice', 'Visiteur enregistré.');

				return new Response ('Le visiteur a été créé.');
			}
		}

		return $this->render('GSBVisiteurBundle:Visiteur:vueFormulaire.html.twig',// en cas d'érreur on revient au formulaire
		array('form' => $form->createView(),));

		}
	}
?>
