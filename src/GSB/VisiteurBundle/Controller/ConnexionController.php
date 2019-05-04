<?php

namespace GSB\VisiteurBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\HttpFoundation\Session\Session;

use GSB\VisiteurBundle\Entity\Visiteur;
use Doctrine\ORM\VisiteurRepository;

class ConnexionController extends Controller{

    public function connecterAction(Request $query) {
        $session= new Session();
        $login=$password=null;

        if($session->get('leVisiteur')!= null){// verifie si la session est deja ouverte
            return $this->redirect('/accueil');
            
        }
        else{
            $connexion = new Visiteur(); // ouvre un nouveau visiteur $connexion contien le matricule le nom et le mot de passe
            $form = $this->createFormBuilder($connexion)// creer la forme du formulaire
            ->add('login',TextType::class)
            ->add('mdp',PasswordType::class)
            ->add('valider',SubmitType::class)
            ->add('annuler',ResetType::class)
            ->getForm();

            if ($query->isMethod('POST')) {
                $form->handleRequest($query);
            }
            if ( $form->isSubmitted() && $form->isValid()) {// la connection a fonctionner
                $data = $query->request->get('form');
                $login= $data['login'];
                $password = $data['mdp'];

                $reponse = $this->getDoctrine()->getManager()->getRepository('GSBVisiteurBundle:Visiteur') ;
                $visiteur= $reponse->findOneBylogin($login);

                if( $visiteur != null && $visiteur->getmdp()==$password){

                    $session->set('leVisiteur',$visiteur);
                    return $this->redirect('/accueil');
                }

            else {
                return $this->render('GSBVisiteurBundle:Visiteur:vueConnexion.html.twig',array('form'=>$form->createView(),'test'=>'Login et/ou mot de passe incorrect(s) '));
                }

            }
        }

        return $this->render('GSBVisiteurBundle:Visiteur:vueConnexion.html.twig',array('form'=>$form->createView(),));

    }

    public function deconnecterAction(Request $request){
        $session = $request->getSession('leVisiteur');
        $session->set('leVisiteur',null);
        $session->set('nbJustificatifs',null);
        return $this->redirect('/');

    }

    public function accueilAction(Request $request){
        $session = $request->getSession('leVisiteur');
        if ($session->get('leVisiteur')== null){
            return $this->redirect('/');
        }
        else{
            return $this->render('GSBVisiteurBundle:Visiteur:Accueil.html.twig',array('leVisiteur'=>$session));
        }

    }


}
