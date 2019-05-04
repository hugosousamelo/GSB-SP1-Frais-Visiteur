<?php

    namespace GSB\VisiteurBundle\Controller;
    use Symfony\Bundle\FrameworkBundle\Controller\Controller;
    use Symfony\Component\Form\Extension\Core\Type\SubmitType;
    use GSB\VisiteurBundle\Entity\FicheFrais;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use GSB\VisiteurBundle\Form\FicheFraisType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use GSB\VisiteurBundle\Entity\Visiteur;

    class FicheFraisController extends Controller{
        public function fichefraisAction(Request $query){

            $fichefrais = new FicheFrais();// creer un objet ficheFrais


            $form = $this->createForm(FicheFraisType::class, $fichefrais);
            $em = $this->getDoctrine()->getManager();

            $session = $query->getSession('leVisiteur');
            if ($session->get('leVisiteur')!= null){

                if ($query->isMethod('POST')) {
                    $form->handleRequest($query);

                        if ($form->isValid()) {
                        $nbJustificatifs = $form["nbJustificatifs"]->getData();
                        $id = $form["id"]->getData();
                        $session->set('nbJustificatifs',$nbJustificatifs);
                        $session->set('id',$id);
                        $em->persist($fichefrais);
                        $em->flush();
                        $query->getSession()->getFlashBag()->add('notice', 'Fiche frais enregistré.');

                        return $this->redirect('/lignefraisforfait/ajouter');
                        }
                }
            }
            else{
                return $this->redirect('/');
            }
            return $this->render('GSBVisiteurBundle:Visiteur:vueFicheFrais.html.twig',array('form'=> $form->createView(),));
        }

        function listerFicheFraisAction(Request $query) {
            $em = $this->getDoctrine()->getManager();

            if($this->get('session')->get('leVisiteur') ==null){
                return $this->redirect('/');
            }else {
                $form = $this->createFormBuilder()
                    ->add('date',TextType::class,array('required'=>false))
                    ->add('Valider', SubmitType::class)
                    ->getForm();

            if ($query->isMethod('POST')) {
                $form->handleRequest($query);

                if ($form->isValid()) {
                    $date = $form["date"]->getData();

                    if ($date !=null){
                        $fichedate = $em->getRepository("GSBVisiteurBundle:FicheFrais")->dateFicheFrais($date);
                        return $this->render('GSBVisiteurBundle:Visiteur:vueListerFicheFrais.html.twig',array('result'=>$fichedate,'form'=>$form->createView(),));
                    }
                    else {
                        $visiteur = $this->get('session')->get('leVisiteur')->getId();
                        $valeur = $em->getRepository("GSBVisiteurBundle:FicheFrais")->listerFicheFrais($visiteur);
                        return $this->render('GSBVisiteurBundle:Visiteur:vueListerFicheFrais.html.twig',array('result'=>$valeur,'form'=>$form->createView(),));
                    }

                }
            }


            $visiteur = $this->get('session')->get('leVisiteur')->getId();
            $valeur = $em->getRepository("GSBVisiteurBundle:FicheFrais")->listerFicheFrais($visiteur);

            return $this->render('GSBVisiteurBundle:Visiteur:vueListerFicheFrais.html.twig',array('result'=>$valeur,'form'=>$form->createView(),));
            }
        }

       function fichefraisModifAction(Request $query,$id){
            $rep = $this->getDoctrine()->getManager()->getRepository('GSBVisiteurBundle:FicheFrais') ;
            $fichefrais= $rep->findOneById($id);
            $form = $this->createForm(FicheFraisType::class, $fichefrais);
            $em = $this->getDoctrine()->getManager();
            if ($query->isMethod('POST')) {
                $form->handleRequest($query);

                if ($form->isValid()) {
                    $em->persist($fichefrais);
                    $em->flush();
                    $query->getSession()->getFlashBag()->add('notice', 'Fiche frais enregistré.');
                    return new Response('La modification a été effectuée.');
                }
            }
            return $this->render('GSBVisiteurBundle:Visiteur:vueFicheFrais.html.twig',array('form'=> $form->createView(),));

        }

        function chercherFicheFraisAction($date){
            $rep = $this->getDoctrine()->getManager()->getRepository('GSBVisiteurBundle:FicheFrais') ;
            $fichefrais = $rep->findOneById($id);
        }

    }




